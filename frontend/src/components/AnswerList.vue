<template>
  <div id="answer-list" class="hidden">
    <div class="heading">
      <h1>List of Answers for Question #{{question_id}}</h1>
    </div>
    <br/>
    <div>
      <button @click="create()">Add</button>
      <button @click="read()">Refresh</button>
    </div>
    <answer-component
            v-for="answer in answers"
            v-bind="answer"
            :key="answer.id"
            @update="update"
            @delete="del"
    ></answer-component>
  </div>
</template>

<script>
    function Answer({ id, content, question_id }) {
        this.id = id;
        this.question_id = question_id;
        this.content = content;
        this.changed = false;

        this.set_content = (new_content) => {
            this.content = new_content;
            this.changed = false;
        }
    }

    import AnswerComponent from './Answer.vue';
    import config from "../config"

    let answer_api_prefix = config.API_LOCATION + 'answers',
        question_api_prefix = config.API_LOCATION + 'questions';

    export default {
        data() {
            return {
                answers: [],
                working: false,
                user_token: null,
                question_id: null,
            }
        },
        methods: {
            set_question_id(question_id) {
                this.question_id = question_id;
                if (question_id) {
                    this.read();
                    this.$el.classList.remove('hidden');
                }
                else {
                    this.$el.classList.add('hidden');
                }
            },
            create() {
                this.mute = true;
                this.axios.post(question_api_prefix + `/${this.question_id}/answer`).then(({ data }) => {
                    this.answers.unshift(new Answer(data));
                    this.mute = false;
                });
            },
            read() {
                this.mute = true;
                this.axios.get(question_api_prefix + `/${this.question_id}`).then(({ data }) => {
                    this.answers = [];
                    data.answers.forEach(answer => {
                        this.answers.unshift(new Answer(answer));
                    });
                    this.mute = false;
                    this.$el.classList.remove("hidden");
                });
            },
            update(id, content) {
                this.mute = true;
                this.axios.put(answer_api_prefix + `/${id}`, { content }).then(({ data }) => {
                    this.answers.find(a => a.id === data.id).set_content(data.content);
                    this.mute = false;
                });
            },
            del(id) {
                this.mute = true;
                this.axios.delete(answer_api_prefix + `/${id}`).then(() => {
                    let index = this.answers.findIndex(answer => answer.id === id);
                    this.answers.splice(index, 1);
                    this.mute = false;
                });
            }
        },
        watch: {
            mute(val) {
                document.getElementById('mute').className = val ? "on" : "";
            }
        },
        components: {
            AnswerComponent
        },
        created() {
            this.$parent.$on('activeQuestion', (question_id) => {
                this.set_question_id(question_id);
            });
        }
    }
</script>
<style>
  #answer-list {
    margin-left: 1em;
  }

  .heading h1 {
    margin-bottom: 0;
  }
</style>
