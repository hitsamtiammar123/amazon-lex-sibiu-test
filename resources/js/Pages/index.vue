<template>
  <amplify-chatbot
    ref="chatBot"
    bot-name="BookTrip_dev"
    bot-title="My ChatBot"
    clearOnComplete="true"
  />
</template>

<script>
import { Inertia } from '@inertiajs/inertia'

export default {
  name: "App",
  mounted() {
    const chatbotElement = this.$refs.chatBot;
    chatbotElement.addEventListener("chatCompleted", this.handleChatComplete);
    },
  beforeUnmount() {
    const chatbotElement = this.$refs.chatBot;
    chatbotElement.removeEventListener(
      "chatCompleted",
      this.handleChatComplete
    );
  },
  methods: {
    handleChatComplete(event) {
      const { data, err } = event.detail;
      const chatList = document.querySelector('amplify-chatbot').shadowRoot.querySelectorAll('.bubble');
      let chatItems = [];
      chatList.forEach(item => {
        const className = item.className;
        const textContent = item.textContent;
        if(!textContent){
            return;
        }
        if(className.search('bot') !== -1){
            chatItems.push({
                from : 'bot',
                content: textContent
            })
        }else if(className.search('user') !== -1){
            chatItems.push({
                from : 'user',
                content: textContent
            })
        }
    });
    const slots = data.slots;
    Inertia.post('/store/', {
        email: slots.Email,
        firstname: slots.Firstname,
        lastname: slots.Lastname,
        email: slots.Email,
        chatlist: chatItems
    })
    },
  },
};
</script>

<style>
#app {
  font-family: Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
  margin-top: 60px;
}
</style>
