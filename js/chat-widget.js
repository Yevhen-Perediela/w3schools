document.addEventListener("DOMContentLoaded", function () {
  const chatToggle = document.getElementById("chat-toggle");
  const chatPopup = document.getElementById("chat-popup");
  const chatClose = document.getElementById("chat-close");
  const chatContainer = document.getElementById("chat-container");
  const messageForm = document.getElementById("message-form");
  const messageInput = document.getElementById("message-input");

  // Toggle chat
  chatToggle.addEventListener("click", () => {
    chatPopup.classList.add("active");
    if (!chatContainer.querySelector(".message")) {
      addMessage(
        "Cześć! Jestem asystentem programowania specjalizującym się w JavaScript, HTML, CSS i PHP. W czym mogę Ci pomóc?",
        "bot"
      );
    }
  });

  chatClose.addEventListener("click", () => {
    chatPopup.classList.remove("active");
  });

  // Reszta kodu z obsługą wiadomości pozostaje taka sama jak w oryginalnym pliku
  messageForm.addEventListener("submit", async (e) => {
    e.preventDefault();
    const message = messageInput.value.trim();
    if (!message) return;

    const button = messageForm.querySelector("button");
    button.disabled = true;

    addMessage(message, "user");
    messageInput.value = "";
    messageInput.disabled = true;

    try {
      const response = await fetch("process_czat.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ message }),
      });

      const data = await response.json();
      if (data.error) {
        addMessage("🚫 " + data.error, "bot");
      } else if (data.response) {
        addMessage(data.response, "bot");
      }
    } catch (error) {
      console.error("Error:", error);
    } finally {
      messageInput.disabled = false;
      messageInput.focus();
      button.disabled = false;
    }
  });

  function addMessage(message, type) {
    const messageDiv = document.createElement("div");
    messageDiv.classList.add("message", `${type}-message`);

    if (type === "bot") {
        try {
            // Funkcja do bezpiecznego escapowania HTML
            function escapeHtml(text) {
                return text
                    .replace(/&/g, "&amp;")
                    .replace(/</g, "&lt;")
                    .replace(/>/g, "&gt;")
                    .replace(/"/g, "&quot;")
                    .replace(/'/g, "&#039;");
            }
            
            // Najpierw escapujemy cały tekst
            let escapedMessage = escapeHtml(message);
            
            // Obsługa bloków kodu
            const codeBlocks = [];
            escapedMessage = escapedMessage.replace(/```(\w+)?\n([\s\S]*?)```/g, function(match, language, code) {
                const placeholder = `CODE_BLOCK_${codeBlocks.length}`;
                codeBlocks.push(`<pre><code class="language-${language || 'plaintext'}">${code.trim()}</code></pre>`);
                return placeholder;
            });
            
            // Zamiana nowych linii na <br>
            escapedMessage = escapedMessage.replace(/\n/g, '<br>');
            
            // Przywracamy bloki kodu
            codeBlocks.forEach((block, index) => {
                escapedMessage = escapedMessage.replace(`CODE_BLOCK_${index}`, block);
            });
            
            messageDiv.innerHTML = escapedMessage;
        } catch (error) {
            console.error('Error formatting message:', error);
            messageDiv.textContent = message;
        }
    } else {
        messageDiv.textContent = message;
    }

    chatContainer.appendChild(messageDiv);
    chatContainer.scrollTop = chatContainer.scrollHeight;

    if (type === "bot") {
        Prism.highlightAllUnder(messageDiv);
    }
  }

  function startCountdown() {
    let seconds = 60;
    const countdownDiv = document.createElement("div");
    countdownDiv.classList.add("countdown");
    chatContainer.appendChild(countdownDiv);

    const interval = setInterval(() => {
      seconds--;
      countdownDiv.textContent = `Spróbuj ponownie za ${seconds} sekund...`;
      if (seconds <= 0) {
        clearInterval(interval);
        countdownDiv.remove();
      }
    }, 1000);
  }
});
