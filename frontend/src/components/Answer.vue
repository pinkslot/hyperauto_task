<template>
  <div class="answer">
    <div>
      <h3> # {{ id }} </h3>
      <!-- Should get allow edit info from backend api to disable buttons -->
      <button @click="del">Delete</button>
      <button @click="save" :disabled=!changed class="save-button">Save changes</button>
    </div>
    <br/>
    <textarea :value=content @input="update" class="content"></textarea>
  </div>
</template>
<script>
    export default {
        methods: {
            save() {
                this.$emit('update', this.id, this.$el.querySelector('.content').value);
            },
            update(val) {
                this.changed = true;
            },
            del() {
                this.$emit('delete', this.id);
            }
        },
        props: ['id', 'content', 'changed', 'question_id'],
    }
</script>
<style>
  .answer {
    margin: 1em 1em 1em 0;
    border: 1px solid #d1d1d1;
    padding: 1em;
    max-height: 400px;
    overflow: hidden;
    background-color: white;
  }
  .answer .textarea-wrapper {
  }
  .answer textarea {
    width: 100%;
    height: 100px;
  }
</style>
