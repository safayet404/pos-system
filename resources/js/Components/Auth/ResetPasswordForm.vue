<script setup>
import { Link, useForm } from '@inertiajs/vue3'

const form = useForm({
    password : ""
})

function resetPassword() {
    form.post('/reset-password', {
        onSuccess: () => {
            form.reset();
        }
    })
}
</script>
<template>
  <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-7 col-lg-6 center-screen">
              <div class="card animated fadeIn w-90 p-4">
                <form @submit.prevent="resetPassword">

                    <div class="card-body">
                        <h4>SET NEW PASSWORD</h4>
                        <br/>
                        <label>New Password</label>
                        <input v-model="form.password" id="password" placeholder="New Password" class="form-control" type="password"/>
                        <br/>
                        <button type="submit" class="btn w-100 btn-success">Next</button>
                    </div>
                </form>
              </div>
          </div>
      </div>
  </div>
</template>


