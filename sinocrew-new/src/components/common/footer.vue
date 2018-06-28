<template>
  <div class="footer pa">
    <p>Copyright ©
      <a href="#">www.ambcon.cn</a>, All Rights Reserved.</p>
    <p>Email : z.zou@ambcon.cn</p>
  </div>
</template>

<script>
export default {
  data() {
    return {};
  },
  components: {},
  created() {}
};
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style scoped>
.footer {
  width: 100%;
  top: 50%;
  transform: translateY(-50%);
  background-color: transparent;
}
p {
  height: 17px;
  padding: 0;
  font-size: 12px;
  color: #CCC;
  text-align: center
}
a{
  color: #CCC
}
</style>
