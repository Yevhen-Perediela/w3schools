<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Asystent Programowania</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism.min.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        #chat-container {
            height: 600px;
            border: 1px solid #ddd;
            overflow-y: auto;
            padding: 20px;
            margin-bottom: 20px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .message {
            margin-bottom: 15px;
            padding: 15px;
            border-radius: 8px;
            max-width: 80%;
        }
        .user-message {
            background-color: #007bff;
            color: white;
            margin-left: auto;
        }
        .bot-message {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
        }
        .bot-message pre {
            background-color: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            overflow-x: auto;
        }
        #message-form {
            display: flex;
            gap: 10px;
        }
        #message-input {
            flex-grow: 1;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        button {
            padding: 12px 24px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        button:disabled {
            background-color: #cccccc;
            cursor: not-allowed;
        }
        .info-box {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .countdown {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h2>Asystent Programowania</h2>
    <div class="info-box">
        <p><strong>Dostępne języki programowania:</strong> JavaScript, HTML, CSS, PHP</p>
        <p>Zadawaj pytania dotyczące programowania w powyższych językach. Asystent pomoże Ci rozwiązać problemy i wyjaśni koncepcje.</p>
    </div>
    
    <div id="chat-container"></div>
    
    <form id="message-form">
        <input type="text" id="message-input" placeholder="Zadaj pytanie dotyczące JS, HTML, CSS lub PHP..." required>
        <button type="submit">Wyślij</button>
    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/prism.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-php.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-markup.min.js"></script>

    <script>
        const chatContainer = document.getElementById('chat-container');
        const messageForm = document.getElementById('message-form');
        const messageInput = document.getElementById('message-input');

       
        addMessage('Cześć! Jestem asystentem programowania specjalizującym się w JavaScript, HTML, CSS i PHP. W czym mogę Ci pomóc?', 'bot');

        messageForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const message = messageInput.value.trim();
            if (!message) return;

            const button = messageForm.querySelector('button');
            button.disabled = true;
            button.textContent = 'Wysyłanie...';
            
            addMessage(message, 'user');
            messageInput.value = '';
            messageInput.disabled = true;

            try {
                const response = await fetch('process_czat.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ message })
                });

                const text = await response.text();
                console.log('Raw response:', text);

                try {
                    const data = JSON.parse(text);
                    if (data.error) {
                        addMessage('🚫 ' + data.error, 'bot');
                        if (data.error.includes('limit zapytań')) {
                            let seconds = 60;
                            const countdownMessage = document.createElement('div');
                            countdownMessage.classList.add('message', 'bot-message', 'countdown');
                            countdownMessage.textContent = `Spróbuj ponownie za ${seconds} sekund...`;
                            chatContainer.appendChild(countdownMessage);
                            
                            const countdown = setInterval(() => {
                                seconds--;
                                countdownMessage.textContent = `Spróbuj ponownie za ${seconds} sekund...`;
                                if (seconds <= 0) {
                                    clearInterval(countdown);
                                    countdownMessage.remove();
                                }
                            }, 1000);
                        }
                    } else if (data.response) {
                        addMessage(data.response, 'bot');
                    }
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                    addMessage('🚫 Nieprawidłowa odpowiedź z serwera', 'bot');
                }
            } catch (networkError) {
                console.error('Network error:', networkError);
                addMessage('🚫 Błąd połączenia z serwerem', 'bot');
            } finally {
                messageInput.disabled = false;
                messageInput.focus();
                button.disabled = false;
                button.textContent = 'Wyślij';
            }
        });

        function addMessage(message, type) {
            const messageDiv = document.createElement('div');
            messageDiv.classList.add('message', `${type}-message`);
            
            if (type === 'bot') {
                try {
                    message = message.replace(/```(\w+)?\n([\s\S]*?)```/g, function(match, language, code) {
                        return `<pre><code class="language-${language || 'plaintext'}">${code.trim()}</code></pre>`;
                    });
                    messageDiv.innerHTML = message;
                } catch (error) {
                    console.error('Error formatting message:', error);
                    messageDiv.textContent = message;
                }
            } else {
                messageDiv.textContent = message;
            }
            
            chatContainer.appendChild(messageDiv);
            chatContainer.scrollTop = chatContainer.scrollHeight;
            
            if (type === 'bot') {
                Prism.highlightAllUnder(messageDiv);
            }
        }
    </script>
</body>
</html>
