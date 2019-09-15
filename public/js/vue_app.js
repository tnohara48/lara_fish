var app = new Vue({
    el: '#app',
    data: {
      message: '',
      keyword: ''
    },
    watch: {
      keyword: function(newKeyword,oldKeyword) {
        console.log(newKeyword)

      }
    }
})