<template>
  <aside class="sidebar" :class="{ 'expanded': isExpanded }">
    <div class="sidebar-header">
      <img src="@/assets/logo.svg" alt="Logo" class="logo" />
      <span class="brand-name" v-show="isExpanded">ProdSphere</span>
    </div>
    
    <nav class="sidebar-nav">
      <ul class="nav-list">
        <li v-for="item in menuItems" :key="item.path" class="nav-item">
          <router-link :to="item.path" class="nav-link" :title="item.name">
            <i :class="item.icon"></i>
            <span class="nav-text" v-show="isExpanded">{{ item.name }}</span>
          </router-link>
        </li>
      </ul>
    </nav>
  </aside>
</template>

<script>
export default {
  name: 'TheSidebar',
  props: {
    isExpanded: {
      type: Boolean,
      default: true
    }
  },
  data() {
    return {
      menuItems: [
        { name: 'Dashboard', path: '/dashboard', icon: 'bi bi-grid-1x2' },
        { name: 'Products', path: '/products', icon: 'bi bi-box' },
        { name: 'Orders', path: '/orders', icon: 'bi bi-cart' },
        { name: 'Customers', path: '/customers', icon: 'bi bi-people' },
        { name: 'Reports', path: '/reports', icon: 'bi bi-graph-up' },
        { name: 'Settings', path: '/settings', icon: 'bi bi-gear' }
      ]
    }
  }
}
</script>

<style scoped>
.sidebar {
  position: fixed;
  left: 0;
  top: 0;
  height: 100vh;
  background-color: #2c3e50;
  color: white;
  width: 70px;
  transition: width 0.3s ease;
  z-index: 1000;
}

.sidebar.expanded {
  width: 250px;
}

.sidebar-header {
  height: 64px;
  display: flex;
  align-items: center;
  padding: 0 1rem;
  border-bottom: 1px solid rgba(255, 255, 255, 0.1);
}

.logo {
  width: 32px;
  height: 32px;
}

.brand-name {
  margin-left: 1rem;
  font-size: 1.2rem;
  font-weight: 600;
  white-space: nowrap;
}

.nav-list {
  list-style: none;
  padding: 1rem 0;
  margin: 0;
}

.nav-link {
  display: flex;
  align-items: center;
  padding: 0.75rem 1rem;
  color: rgba(255, 255, 255, 0.7);
  text-decoration: none;
  transition: all 0.3s ease;
}

.nav-link:hover {
  background-color: rgba(255, 255, 255, 0.1);
  color: white;
}

.nav-link i {
  font-size: 1.2rem;
  width: 24px;
}

.nav-text {
  margin-left: 1rem;
  white-space: nowrap;
}

.router-link-active {
  background-color: rgba(255, 255, 255, 0.1);
  color: white;
  border-left: 3px solid #41b883;
}
</style> 