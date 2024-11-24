<template>
  <div class="container">
    <div class="row justify-content-center min-vh-100 align-items-center">
      <div class="col-md-5">
        <div class="card shadow">
          <div class="card-body p-5">
            <h2 class="text-center mb-4">Register</h2>

            <form @submit.prevent="handleRegister">
              <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>

                <input type="text" class="form-control" id="name" v-model="name" required />
              </div>

              <div class="mb-3">
                <label for="email" class="form-label">Email address</label>

                <input type="email" class="form-control" id="email" v-model="email" required />
              </div>

              <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <div class="input-group">
                  <input :type="showPassword ? 'text' : 'password'" class="form-control" id="password"
                    v-model="password" required />
                  <button class="btn btn-outline-secondary" type="button" @click="showPassword = !showPassword">
                    <i :class="showPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                  </button>
                </div>
              </div>

              <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <div class="input-group">
                  <input :type="showConfirmPassword ? 'text' : 'password'" class="form-control" id="confirmPassword"
                    v-model="confirmPassword" required />
                  <button class="btn btn-outline-secondary" type="button"
                    @click="showConfirmPassword = !showConfirmPassword">
                    <i :class="showConfirmPassword ? 'bi bi-eye-slash' : 'bi bi-eye'"></i>
                  </button>
                </div>
              </div>

              <button type="submit" class="btn btn-primary w-100 mb-3">Register</button>

              <p class="text-center mb-0">
                Already have an account?

                <router-link to="/login">Login here</router-link>
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
import { useAuthStore } from '@/stores/auth'
export default {
  name: 'RegisterView',

  data() {
    return {
      name: '',

      email: '',

      password: '',

      confirmPassword: '',

      showPassword: false,

      showConfirmPassword: false,
    }
  },

  methods: {
    async handleRegister() {
      try {
        // Implement registration logic here
        const auth = useAuthStore()
        const response = await api.post('/register', {
          name: this.name,
          email: this.email,
          password: this.password,
        })
        console.log('Register response:', response);
        if (response.status === 200) {
          console.log('Register successful');
          localStorage.setItem('token', response.data.token);
          localStorage.setItem('user', JSON.stringify(response.data.user));
          auth.setToken(response.data.token)
          auth.setUser(response.data.user)
          this.$router.push('/dashboard');
        } else {
          console.log('Register failed');
        }
      } catch (error) {
        console.error('Register failed:', error);
      }
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
