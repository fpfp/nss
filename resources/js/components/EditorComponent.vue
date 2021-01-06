<template>
  <div>
    Design Editor
  </div>
</template>

<script>
export default {
  created() {
    //console.log("Component Design Editor created.");
  },
  mounted() {
  },
  computed: {
    user: function () {
      return this.$store.state.user;
    },
  },
  data() {
    return {
      tshirt: null,
    };
  },
  methods: {
    
    
  },
};
</script>
