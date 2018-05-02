<template>
  <div id="app" class="row">
    <div class="col-6">
      <question-list-component/>
    </div>
    <div class="col-6">
      <answer-list-component/>
    </div>
  </div>
</template>

<script>
    import QuestionListComponent from './components/QuestionList.vue';
    import AnswerListComponent from './components/AnswerList.vue';

    export default {
        components: {
            QuestionListComponent,
            AnswerListComponent,
        },
    }
</script>
<style>
  .hidden {
    display: none
  }

  .heading h1 {
    margin-bottom: 0;
  }
</style>
