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
        if (data.error.includes("limit zapytań")) {
          startCountdown();
        }
      } else if (data.response) {
        addMessage(data.response, "bot");
      }
    } catch (error) {
      console.error("Error:", error);
      addMessage(
        "Przepraszam, wystąpił błąd. Spróbuj ponownie później.",
        "bot"
      );
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
        message = message.replace(
          /```(\w+)?\n([\s\S]*?)```/g,
          function (match, language, code) {
            return `<pre><code class="language-${
              language || "plaintext"
            }">${code.trim()}</code></pre>`;
          }
        );
        messageDiv.innerHTML = message;
      } catch (error) {
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
