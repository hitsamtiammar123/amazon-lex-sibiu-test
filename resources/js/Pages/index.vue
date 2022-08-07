<template>
  <amplify-chatbot
    ref="chatBot"
    bot-name="BookTrip_dev"
    bot-title="My ChatBot"
    clearOnComplete="true"
  />
</template>

<script>
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
      if (data) alert("success!\n" + JSON.stringify(data));
      if (err) alert(err);
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
