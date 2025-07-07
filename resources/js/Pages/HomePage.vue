<script setup >
import { Link } from '@inertiajs/vue3';

</script>



<template>
  <div class="wrapper">
    <Link class="start-btn btn btn-success" href="/dashboard">Start</Link>
  </div>
</template>

<style scoped>

.wrapper{
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
}
.start-btn {
    margin: auto;
    padding: 5px 80px 5px 80px;
    border: 1px solid ;
}


</style>