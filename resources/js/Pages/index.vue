<template>
    <div class="container mt-2 d-flex flex-column">
        <h1>Chat List</h1>
        <div ref="chatWrapper" class="chat-wrapper">
            <div class="chat-container">
                <div class="chat-list">
                    <template :key="item.id"  v-for="item in listChat">
                        <div v-if="item.type === 'chat-content'" :class="`chat-item ${item.from === 'bot' ? 'bot' : ''}`">
                            {{item.content}}
                        </div>
                        <div v-else-if="item.type === 'chat-done'" class="d-flex col-12 justify-content-center">
                            <div class="chat-item chat-done">Chat is Fulfilled</div>
                        </div>
                        <div v-else-if="item.type === 'chat-failed'" class="d-flex col-12 justify-content-center">
                             <div class="chat-item chat-failed">Please talk properly to me</div>
                        </div>
                    </template>
                </div>
            </div>
        </div>
        <form  @submit.prevent="onSend" class="row mt-3">
            <div class="col-auto flex-1">
                <label for="chat" class="visually-hidden">Chat</label>
                <input ref="inputChat" :disabled="textDisabled" type="text"
                    v-model="textInput" class="form-control flex-1" :placeholder="placeholder"
                    id="chat" />
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary mb-3">Send</button>
            </div>
        </form>
    </div>
</template>

<script>
export default {
    name: "App",

    data(){
        return {
            textInput: '',
            listChat: [],
            textDisabled: false,
            placeholder: 'Please add chat here',
            currentId: this.generateId(),
            status: 'ongoing'
        }
    },
    watch:{
        listChat(){
            }
    },
    updated(){
        this.$refs.chatWrapper.scrollTop = this.$refs.chatWrapper.scrollHeight;
    },
    methods: {
        generateId(){
            return 'user-' + new Date().getTime() + '-' + (Math.floor(Math.random() * 1000))
        },
        generateChatId(){
            return new Date().getTime() + '-' + this.listChat.length;
        },
        sendDataToDb(slots){
            const filteredData = this.listChat
                .filter(item => item.type === 'chat-content')
                .map(item => ({
                    from: item.from,
                    content: item.content
            }))
            this.axios.post('api/store', {
                firstname: slots.Firstname,
                lastname: slots.Lastname,
                email: slots.Email,
                chatlist: filteredData
            })
        },
        onSend(){
            console.log('Hello', { textInput: this.textInput });
            this.listChat.push({
                id: this.generateChatId(),
                from: 'user',
                content: this.textInput,
                type: 'chat-content'
            })
            this.textDisabled = true;
            this.placeholder = 'Waiting for bot...'


            this.axios.post('api/chat', {
                inputText: this.textInput,
                userId: this.currentId
            }).then(response => {
                const data = response.data;
                const dialogState = data.dialogState;
                if(dialogState === 'Fulfilled' || dialogState === 'Failed'){
                    this.currentId = this.generateId()
                }
                this.listChat.push({
                    id: this.generateChatId(),
                    from: 'bot',
                    content: data.message,
                    type: 'chat-content'
                });

                if(dialogState === 'Fulfilled'){
                    this.listChat.push({
                        id: this.generateChatId(),
                        type: 'chat-done'
                    });
                    this.sendDataToDb(data.slots)
                }else if(dialogState === 'Failed'){
                    this.listChat.push({
                        id: this.generateChatId(),
                        type: 'chat-failed'
                    });
                }

            }).catch((err) => {
                console.log('An error occured', { err })
            }).finally(() => {
                 this.textDisabled = false;
                this.placeholder = 'Please add chat here'
                this.$refs.inputChat.focus();
            })
            this.textInput = '';
        },

        handleChatComplete(event) {
        },
    },
};
</script>

<style lang="scss">
    .chat-wrapper{
        display: block;
        height: 500px;
        overflow: auto;
    }

    .chat-container{
        display: flex;
        min-height: 500px;
        flex-direction: column;
    }

    .chat-list{
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        flex: 1;
        overflow: auto;
        border: 1px solid lightgray;
        border-radius: 20px;
        padding: 20px;

        .chat-item{
            display: flex;
            width: 50%;
            min-height: 40px;
            border-radius: 20px;
            background-color: lightblue;
            align-items: center;
            padding: 10px 20px;
            margin-top: 30px;
            margin-left: 50%;

            &.bot{
                background-color: lightgrey;
                margin-left: 0;
            }

            &.chat-done, &.chat-failed{
                height: 35px;
                min-height: 0;
                justify-content: center;
                padding: 0;
                width: 25%;
                margin-left: 0;
            }

            &.chat-done{
                background-color: lightgreen;
            }

            &.chat-failed{
                background-color: lightcoral;
            }
        }

    }

    .flex-1{
        flex: 1;
    }
</style>
