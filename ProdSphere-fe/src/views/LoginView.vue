<template>
  <div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
      <div class="col-md-5">
        <div class="card shadow">
          <div class="card-body p-5">
            <h2 class="text-center mb-4">Login</h2>
            <form @submit.prevent="handleLogin">
              <div class="mb-3">
                <label for="email" class="form-label">Email address</label>
                <input type="email" class="form-control" id="email" v-model="email" required />
              </div>
              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input
                  type="password"
                  class="form-control"
                  id="password"
                  v-model="password"
                  required
                />
              </div>
              <button type="submit" class="btn btn-primary w-100 mb-3">Login</button>
              <p class="text-center mb-0">
                Don't have an account?
                <router-link to="/register">Register here</router-link>
              </p>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import api from '@/api'

export default {
  name: 'LoginView',
  data() {
    return {
      email: '',
      password: '',
    }
  },
  methods: {
    handleLogin() {
      // Implement login logic here
      console.log('Login attempt:', this.email, this.password)
      api.post('/login', {  
        email: this.email,
        password: this.password,
      })
    },
  },
}
</script>

<style scoped>
.card {
  border-radius: 1rem;
  border: none;
}

.btn-primary {
  padding: 0.8rem;
  border-radius: 0.5rem;
}
</style>
