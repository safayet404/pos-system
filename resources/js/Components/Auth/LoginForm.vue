<script setup>
import { Link, useForm, router } from '@inertiajs/vue3'
const form = useForm({
    email : '',
    password : ''
})

function submit() {
  router.post('/login', form.data(), {
    onSuccess: () => {
      router.get('/dashboard')
    }
  })
}
</script>

<template>
  <div class="container">
      <div class="row justify-content-center">
          <div class="col-md-7 animated fadeIn col-lg-6 center-screen">
              <div class="card w-90  p-4">
                <form @submit.prevent="submit">

                    <div class="card-body">
                        <h4>SIGN IN</h4>
                        <br/>
                        <input id="email" v-model="form.email" placeholder="User Email" class="form-control" type="email"/>
                        <br/>
                        <input id="password"  v-model="form.password" placeholder="User Password" class="form-control" type="password"/>
                        <br/>
                        <button type='submit'   class="btn w-100 btn-success">Next</button>
                        <hr/>
                        <div class="float-end mt-3">
                        <span>
                            <Link class="text-center ms-3 h6" href="/RegistrationPage">Sign Up </Link>
                            <span class="ms-1">|</span>
                            <Link class="text-center ms-3 h6" href="/SendOtpPage">Forget Password</Link>
                        </span>
                        </div>
                    </div>
                </form>
              </div>
          </div>
      </div>
  </div>
</template>


