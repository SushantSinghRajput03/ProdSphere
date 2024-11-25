<template>
  <DashboardLayout>
    <div class="dashboard-content">
      <div class="row">
        <!-- Stats Cards -->
        <div class="col-md-3 mb-4" v-for="stat in stats" :key="stat.title">
          <div class="card stat-card">
            <div class="card-body">
              <div class="stat-icon" :class="stat.color">
                <i :class="stat.icon"></i>
              </div>
              <div class="stat-details">
                <h3 class="stat-value">{{ stat.value }}</h3>
                <p class="stat-title">{{ stat.title }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Recent Activity -->
      <div class="row mt-4">
        <div class="col-md-8">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title mb-0">Recent Activity</h5>
            </div>
            <div class="card-body">
              <div class="activity-item" v-for="(activity, index) in recentActivities" :key="index">
                <div class="activity-content">
                  <div class="activity-icon" :class="activity.iconClass">
                    <i :class="activity.icon"></i>
                  </div>
                  <div class="activity-details">
                    <p class="activity-text">{{ activity.text }}</p>
                    <small class="activity-time">{{ activity.time }}</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div class="col-md-4">
          <div class="card">
            <div class="card-header">
              <h5 class="card-title mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
              <div class="quick-actions">
                <button class="btn btn-primary mb-2 w-100">
                  <i class="bi bi-plus-circle"></i> Add New Product
                </button>
                <button class="btn btn-outline-primary mb-2 w-100">
                  <i class="bi bi-cart-plus"></i> Create Order
                </button>
                <button class="btn btn-outline-primary w-100">
                  <i class="bi bi-person-plus"></i> Add Customer
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </DashboardLayout>
</template>

<script>
import { ref, onMounted } from 'vue'
import DashboardLayout from '@/layouts/DashboardLayout.vue'
import api from '@/api'
import { useAuthStore } from '@/stores/auth'

export default {
  name: 'DashboardView',
  components: {
    DashboardLayout
  },
  setup() {
    const totalProducts = ref(0)
    
    const fetchTotalProducts = async () => {
      try {
        const auth = useAuthStore()
        api.defaults.headers.common['Authorization'] = `Bearer ${auth.token}`
        const response = await api.get('/products')
        totalProducts.value = response.data.data.total
      } catch (error) {
        console.error('Error fetching total products:', error)
      }
    }

    onMounted(() => {
      fetchTotalProducts()
    })

    return {
      stats: [
        { title: 'Total Sales', value: '$24,580', icon: 'bi bi-currency-dollar', color: 'bg-primary' },
        { title: 'Total Orders', value: '145', icon: 'bi bi-cart', color: 'bg-success' },
        { title: 'Total Products', value: totalProducts, icon: 'bi bi-box', color: 'bg-warning' },
        { title: 'Total Customers', value: '524', icon: 'bi bi-people', color: 'bg-info' }
      ],
      recentActivities: [
        {
          text: 'New order #1234 from John Doe',
          time: '5 minutes ago',
          icon: 'bi bi-cart',
          iconClass: 'bg-primary'
        },
        {
          text: 'Product "iPhone 13" stock updated',
          time: '2 hours ago',
          icon: 'bi bi-box',
          iconClass: 'bg-success'
        },
        {
          text: 'New customer registration',
          time: '3 hours ago',
          icon: 'bi bi-person',
          iconClass: 'bg-info'
        }
      ]
    }
  }
}
</script>

<style scoped>
.dashboard-content {
  padding: 1rem;
}

.stat-card {
  border: none;
  border-radius: 0.5rem;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.stat-card .card-body {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.stat-icon {
  width: 48px;
  height: 48px;
  border-radius: 0.5rem;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 1.5rem;
  color: white;
}

.bg-primary { background-color: #4f46e5; }
.bg-success { background-color: #10b981; }
.bg-warning { background-color: #f59e0b; }
.bg-info { background-color: #3b82f6; }

.stat-details {
  flex: 1;
}

.stat-value {
  font-size: 1.5rem;
  font-weight: 600;
  margin: 0;
}

.stat-title {
  color: #6b7280;
  margin: 0;
}

.activity-item {
  padding: 1rem 0;
  border-bottom: 1px solid #e5e7eb;
}

.activity-item:last-child {
  border-bottom: none;
}

.activity-content {
  display: flex;
  align-items: center;
  gap: 1rem;
}

.activity-icon {
  width: 40px;
  height: 40px;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
  color: white;
}

.activity-details {
  flex: 1;
}

.activity-text {
  margin: 0;
  font-weight: 500;
}

.activity-time {
  color: #6b7280;
}

.quick-actions {
  display: flex;
  flex-direction: column;
  gap: 0.5rem;
}
</style>
