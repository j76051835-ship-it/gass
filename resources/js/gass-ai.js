const gassAI = document.querySelector("[data-gass-ai]");

if (gassAI) {
    const toggle = gassAI.querySelector("[data-gass-ai-toggle]");
    const close = gassAI.querySelector("[data-gass-ai-close]");
    const panel = gassAI.querySelector("[data-gass-ai-panel]");
    const messages = gassAI.querySelector("[data-gass-ai-messages]");
    const typing = gassAI.querySelector("[data-gass-ai-typing]");
    const unread = gassAI.querySelector("[data-gass-ai-unread]");
    let conversationId = null;
    let isSending = false;

    const setOpen = (isOpen) => {
        panel.classList.toggle("is-open", isOpen);
        panel.setAttribute("aria-hidden", String(!isOpen));
        toggle.setAttribute("aria-expanded", String(isOpen));
        unread.hidden = isOpen;
    };

    const addMessage = (message, role) => {
        const element = document.createElement("div");
        element.className = `gass-ai-message is-${role}`;
        element.textContent = message;
        messages.append(element);
        messages.scrollTop = messages.scrollHeight;
    };

    const addFollowUpQuestions = (questions) => {
        if (!Array.isArray(questions) || questions.length === 0) return;
        const actions = document.createElement("div");
        actions.className = "gass-ai-quick gass-ai-follow-ups";
        const label = document.createElement("p");
        label.textContent = "Pertanyaan lanjutan:";
        actions.append(label);
        questions.forEach((question) => {
            const button = document.createElement("button");
            button.type = "button";
            button.dataset.gassAiPrompt = question;
            button.textContent = question;
            button.addEventListener("click", () => sendMessage(question));
            actions.append(button);
        });
        messages.append(actions);
        messages.scrollTop = messages.scrollHeight;
    };

    const sendMessage = async (message) => {
        if (isSending || !message.trim()) return;
        isSending = true;
        addMessage(message.trim(), "user");
        typing.hidden = false;
        messages.scrollTop = messages.scrollHeight;

        try {
            const response = await fetch("/ai/chat", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "Accept": "application/json",
                    "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content,
                },
                body: JSON.stringify({ message: message.trim(), conversation_id: conversationId, quick_action: true }),
            });
            const data = await response.json();
            if (!response.ok) {
                if (response.status === 429) {
                    const retryAfter = Number(response.headers.get("Retry-After"));
                    const waitMessage = retryAfter > 0 ? ` Coba lagi dalam sekitar ${retryAfter} detik.` : " Coba lagi sebentar lagi.";
                    throw new Error(`Batas pertanyaan sementara tercapai.${waitMessage}`);
                }
                throw new Error(data.message || "GASS AI sedang tidak tersedia.");
            }
            conversationId = data.conversation_id;
            addMessage(data.message, "assistant");
            addFollowUpQuestions(data.follow_up_questions);
            if (!panel.classList.contains("is-open")) unread.hidden = false;
        } catch (error) {
            addMessage(error.message || "Maaf, GASS AI sedang mengalami gangguan. Silakan coba lagi.", "assistant");
        } finally {
            typing.hidden = true;
            isSending = false;
        }
    };

    toggle.addEventListener("click", () => setOpen(!panel.classList.contains("is-open")));
    close.addEventListener("click", () => setOpen(false));
    gassAI.querySelectorAll("[data-gass-ai-prompt]").forEach((button) => {
        button.addEventListener("click", () => sendMessage(button.dataset.gassAiPrompt));
    });
    document.addEventListener("keydown", (event) => {
        if (event.key === "Escape" && panel.classList.contains("is-open")) setOpen(false);
    });
}
