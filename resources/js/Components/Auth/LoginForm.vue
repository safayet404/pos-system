<script setup>
import { useForm, Link } from '@inertiajs/vue3'

const form = useForm({
  email: '',
  password: ''
})

function submit() {
  form.post('/login', {
    onFinish: () => {
      form.reset('password')
    }
  })
}
</script>

<template>
  <div class="container">
    <div class="row justify-content-center">
      <div class="col-md-7 col-lg-6 center-screen">
        <div class="card w-90 p-4 animated fadeIn">
          <form @submit.prevent="submit">
            <div class="card-body">
              <h4>SIGN IN</h4>
              <br />

              <input
                id="email"
                v-model="form.email"
                placeholder="User Email"
                class="form-control"
                type="email"
              />
              <div v-if="form.errors.email" class="text-danger">{{ form.errors.email }}</div>

              <br />

              <input
                id="password"
                v-model="form.password"
                placeholder="User Password"
                class="form-control"
                type="password"
              />
              <div v-if="form.errors.password" class="text-danger">{{ form.errors.password }}</div>

              <br />

              <button type="submit" class="btn w-100 btn-success" :disabled="form.processing">
                {{ form.processing ? 'Logging in...' : 'Next' }}
              </button>

              <hr />

              <div class="float-end mt-3">
                <span>
                  <Link class="text-center ms-3 h6" href="/RegistrationPage">Sign Up</Link>
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
