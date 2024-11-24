<template>
  <header class="header">
    <div class="header-left">
      <button class="toggle-btn" @click="$emit('toggle-sidebar')">
        <i class="bi bi-list"></i>
      </button>
      <div class="breadcrumb">
        <h1 class="page-title">{{ currentPage }}</h1>
      </div>
    </div>
    <div class="header-right">
      <div class="header-actions">
        <button class="action-btn" title="Notifications">
          <i class="bi bi-bell"></i>
          <span class="badge">3</span>
        </button>
        <div class="user-profile" @click="toggleUserMenu">
          <img :src="`https://ui-avatars.com/api/?name=${userName}`" alt="User" class="avatar" />
          <span class="user-name">{{ userName }}</span>
          <i class="bi bi-chevron-down"></i>
          
          <div class="user-menu" v-show="showUserMenu">
            <router-link to="/profile" class="menu-item">
              <i class="bi bi-person"></i> Profile
            </router-link>
            <router-link to="/settings" class="menu-item">
              <i class="bi bi-gear"></i> Settings
            </router-link>
            <div class="divider"></div>
            <a href="#" class="menu-item text-danger" @click.prevent="handleLogout">
              <i class="bi bi-box-arrow-right"></i> Logout
            </a>
          </div>
        </div>
      </div>
    </div>
  </header>
</template>

<script>
import api from '@/api'
import { useAuthStore } from '@/stores/auth'

export default {
  name: 'TheHeader',
  data() {
    return {
      showUserMenu: false
    }
  },
  computed: {
    currentPage() {
      return this.$route.name || 'Dashboard'
    },
    userName() {
      const auth = useAuthStore()
      return auth.user?.name || 'User'
    }
  },
  methods: {
    toggleUserMenu() {
      this.showUserMenu = !this.showUserMenu
    },
    async handleLogout() {
      try {
        const auth = useAuthStore()
        const response = await api.post('/logout')
        
        if (response.status === 200) {
          auth.clearAuth()
          this.$router.push('/login')
        }
      } catch (error) {
        console.error('Logout failed:', error)
        // Handle error
      }
    }
  }
}
</script>

<style scoped>
.header {
  height: 64px;
  background-color: white;
  border-bottom: 1px solid #e5e7eb;
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 1.5rem;
  position: sticky;
  top: 0;
  z-index: 100;
}

.header-left {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.toggle-btn {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
  padding: 0.5rem;
  color: #6b7280;
}

.page-title {
  font-size: 1.25rem;
  font-weight: 600;
  color: #111827;
  margin: 0;
}

.header-actions {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.action-btn {
  background: none;
  border: none;
  position: relative;
  padding: 0.5rem;
  font-size: 1.25rem;
  color: #6b7280;
  cursor: pointer;
}

.badge {
  position: absolute;
  top: 0;
  right: 0;
  background-color: #ef4444;
  color: white;
  font-size: 0.75rem;
  padding: 0.25rem 0.5rem;
  border-radius: 9999px;
}

.user-profile {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  cursor: pointer;
  position: relative;
  padding: 0.5rem;
}

.avatar {
  width: 32px;
  height: 32px;
  border-radius: 50%;
}

.user-name {
  font-weight: 500;
  color: #374151;
}

.user-menu {
  position: absolute;
  top: 100%;
  right: 0;
  background-color: white;
  border: 1px solid #e5e7eb;
  border-radius: 0.5rem;
  box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
  min-width: 200px;
  padding: 0.5rem 0;
  margin-top: 0.5rem;
}

.menu-item {
  display: flex;
  align-items: center;
  gap: 0.5rem;
  padding: 0.5rem 1rem;
  color: #374151;
  text-decoration: none;
}

.menu-item:hover {
  background-color: #f3f4f6;
}

.divider {
  height: 1px;
  background-color: #e5e7eb;
  margin: 0.5rem 0;
}

.text-danger {
  color: #dc2626;
}
</style> 