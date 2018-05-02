<template>
  <div id="question-list">
    <div class="heading">
      <h1>List of Questions (click item to view answers)</h1>
    </div>
    <br/>
    <div>
      <button @click="create()">Add</button>
      <button @click="read()">Refresh</button>
    </div>
    <question-component
            v-for="question in questions"
            v-bind="question"
            :key="question.id"
            @update="update"
            @delete="del"
            @active="active"
    ></question-component>
  </div>
</template>

<script>
    function Question({ id, content }) {
        this.id = id;
        this.content = content;
        this.changed = false;
        this.activeCss = "";

        this.set_content = (new_content) => {
            this.content = new_content;
            this.changed = false;
        }
    }

    import QuestionComponent from './Question.vue';
    import config from "../config"

    let question_api_prefix = config.API_LOCATION + 'questions';

    export default {
        data() {
            return {
                questions: [],
                working: false,
                user_token: null,
            }
        },
        methods: {
            active(id) {
                this.questions.forEach(question => {
                    question.activeCss = question.id === id ? 'active' : '';
                });
                this.$parent.$emit('activeQuestion', id);
            },
            create() {
                this.mute = true;
                this.axios.post(question_api_prefix).then(({ data }) => {
                    this.questions.unshift(new Question(data));
                    this.mute = false;
                });
            },
            read($auth = false) {
                this.mute = true;
                this.axios.get(question_api_prefix).then(({ data, headers }) => {
                    data.forEach(question => {
                        this.questions.unshift(new Question(question));
                    });
                    this.mute = false;
                    this.$parent.$emit('activeQuestion', null);

                    if ($auth) {
                        // something changes header name capitalizing
                        this.user_token = headers['authorization'];
                        this.axios.defaults.headers.common['Authorization'] = this.user_token;
                    }
                });

            },
            update(id, content) {
                this.mute = true;
                this.axios.put(question_api_prefix + `/${id}`, { content }).then(({ data }) => {
                    this.questions.find(q => q.id === data.id).set_content(data.content);
                    this.mute = false;
                });
            },
            del(id) {
                this.mute = true;
                this.axios.delete(question_api_prefix + `/${id}`).then(() => {
                    let index = this.questions.findIndex(question => question.id === id),
                        question = this.questions[index];

                    if (question.activeCss) {
                        this.$parent.$emit('activeQuestion', null);
                    }
                    this.questions.splice(index, 1);
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
            QuestionComponent
        },
        created() {
            this.read(true);
        }
    }
</script>
<style>
  #question-list {
    margin-left: 1em;
  }

  .heading h1 {
    margin-bottom: 0;
  }
</style>
