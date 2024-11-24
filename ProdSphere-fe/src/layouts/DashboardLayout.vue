<template>
  <div class="dashboard-layout">
    <TheSidebar :is-expanded="isSidebarExpanded" />
    <div class="main-content" :class="{ 'sidebar-expanded': isSidebarExpanded }">
      <TheHeader @toggle-sidebar="toggleSidebar" />
      <main class="content">
        <slot></slot>
      </main>
    </div>
  </div>
</template>

<script>
import TheSidebar from '@/components/TheSidebar.vue'
import TheHeader from '@/components/TheHeader.vue'

export default {
  name: 'DashboardLayout',
  components: {
    TheSidebar,
    TheHeader
  },
  data() {
    return {
      isSidebarExpanded: true
    }
  },
  methods: {
    toggleSidebar() {
      this.isSidebarExpanded = !this.isSidebarExpanded
    }
  }
}
</script>

<style scoped>
.dashboard-layout {
  display: flex;
  min-height: 100vh;
}

.main-content {
  flex: 1;
  margin-left: 70px;
  transition: margin-left 0.3s ease;
}

.main-content.sidebar-expanded {
  margin-left: 250px;
}

.content {
  padding: 2rem;
  background-color: #f8f9fa;
  min-height: calc(100vh - 64px);
}
</style> 