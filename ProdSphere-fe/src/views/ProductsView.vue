<template>
  <DashboardLayout>
    <div class="products-page">
      <!-- Filters -->
      <div class="card mb-4">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-md-3">
              <input v-model="filters.search" type="text" class="form-control" placeholder="Search products..."
                @input="debounceSearch">
            </div>
            <div class="col-md-2">
              <select v-model="filters.category" class="form-select" @change="debounceSearch">
                <option :value="null">All Categories</option>
                <option v-for="category in categories" :key="category" :value="category">
                  {{ category }}
                </option>
              </select>
            </div>
            <div class="col-md-2">
              <input v-model="filters.min_price" type="number" class="form-control" placeholder="Min Price"
                @input="debounceSearch">
            </div>
            <div class="col-md-2">
              <input v-model="filters.max_price" type="number" class="form-control" placeholder="Max Price"
                @input="debounceSearch">
            </div>
            <div class="col-md-3">
              <button class="btn btn-primary w-100" @click="openAddModal">
                <i class="bi bi-plus-circle"></i> Add Product
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Products Grid -->
      <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
        <template v-if="products.data && products.data.length > 0">
          <div class="col" v-for="product in products.data" :key="product.id">
            <div class="card h-100">
              <img :src="imageUrl(product.image) || '/placeholder.png'" class="card-img-top product-image" alt="Product">
              <div class="card-body">
                <h5 class="card-title">{{ product.name }}</h5>
                <p class="card-text text-truncate">{{ product.description }}</p>
                <div class="d-flex justify-content-between align-items-center mb-2">
                  <span class="price">${{ product.price }}</span>
                  <span class="stock">Stock: {{ product.stock }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                  <span class="badge bg-secondary">{{ product.category }}</span>
                </div>
              </div>
              <div class="card-footer bg-transparent border-top-0">
                <div class="btn-group w-100">
                  <button class="btn btn-outline-primary" @click="openEditModal(product)">
                    <i class="bi bi-pencil"></i> Edit
                  </button>
                  <button class="btn btn-outline-danger" @click="deleteProduct(product)">
                    <i class="bi bi-trash"></i> Delete
                  </button>
                </div>
              </div>
            </div>
          </div>
        </template>
        <div v-else class="text-center w-100">
          <p>No products found</p>
        </div>
      </div>

      <!-- Pagination -->
      <nav v-if="products.last_page > 1" class="d-flex justify-content-center mt-4">
        <ul class="pagination">
          <li class="page-item" :class="{ disabled: products.current_page === 1 }">
            <a class="page-link" href="#" @click.prevent="changePage(products.current_page - 1)">Previous</a>
          </li>
          <li v-for="page in products.last_page" :key="page" class="page-item" :class="{ active: page === products.current_page }">
            <a class="page-link" href="#" @click.prevent="changePage(page)">{{ page }}</a>
          </li>
          <li class="page-item" :class="{ disabled: products.current_page === products.last_page }">
            <a class="page-link" href="#" @click.prevent="changePage(products.current_page + 1)">Next</a>
          </li>
        </ul>
      </nav>

      <!-- Add/Edit Modal -->
      <div class="modal fade show" v-if="showAddModal || editingProduct" style="display: block">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">{{ editingProduct ? 'Edit' : 'Add' }} Product</h5>
              <button type="button" class="btn-close" @click="closeModal"></button>
            </div>
            <div class="modal-body">
              <form @submit.prevent="handleSubmit">
                <div class="mb-3">
                  <label class="form-label">Name</label>
                  <input v-model="productForm.name" type="text" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Description</label>
                  <textarea v-model="productForm.description" class="form-control" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                  <label class="form-label">Price</label>
                  <input v-model="productForm.price" type="number" step="0.01" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Category</label>
                  <input v-model="productForm.category" type="text" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Stock</label>
                  <input v-model="productForm.stock" type="number" class="form-control" required>
                </div>
                <div class="mb-3">
                  <label class="form-label">Image</label>
                  <input 
                    type="file" 
                    class="form-control" 
                    @change="handleImageChange" 
                    accept="image/*"
                  >
                  <div v-if="imagePreview" class="mt-2">
                    <img :src="imagePreview" class="img-thumbnail" style="max-height: 200px">
                  </div>
                  <div v-if="editingProduct && editingProduct.image" class="mt-2">
                    <img :src="imageUrl(editingProduct.image)" class="img-thumbnail" style="max-height: 200px">
                  </div>
                </div>
                <div class="text-end">
                  <button type="button" class="btn btn-secondary me-2" @click="closeModal">Cancel</button>
                  <button type="submit" class="btn btn-primary">Save</button>
                </div>
              </form>
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

const debounce = (fn, delay) => {
  let timeoutId
  return (...args) => {
    clearTimeout(timeoutId)
    timeoutId = setTimeout(() => fn(...args), delay)
  }
}

export default {
  name: 'ProductsView',
  components: { DashboardLayout },
  setup() {
    const products = ref({
      data: [],
      current_page: 1,
      last_page: 1,
      total: 0,
      per_page: 10
    })
    const filters = ref({
      search: null,
      category: null,
      min_price: null,
      max_price: null
    })
    const imagePreview = ref(null)
    const currentPage = ref(1)
    const totalPages = ref(1)
    const showAddModal = ref(false)
    const editingProduct = ref(null)
    const productForm = ref({
      name: '',
      description: '',
      price: '',
      category: '',
      stock: '',
      image: null
    })
    const categories = ref([])

    const imageUrl = (image) => {
      return `${import.meta.env.VITE_API_URL}/storage/products/${image}`
    }

    const loadProducts = async () => {
      try {
        const auth = useAuthStore()
        api.defaults.headers.common['Authorization'] = `Bearer ${auth.token}`
        const response = await api.get('/products', {
          params: {
            ...Object.fromEntries(
              Object.entries(filters.value).filter(([, value]) => value !== null && value !== '')
            ),
            page: currentPage.value
          }
        })
        products.value = response.data.data
        if(categories.value.length === 0) {
          const uniqueCategories = new Set(response.data.data.data.map(product => product.category))
          categories.value = Array.from(uniqueCategories).filter(Boolean).sort()
        }
        console.log('categories.value : ', categories.value)
      } catch (error) {
        console.error('Error loading products:', error)
      }
    }

    const openAddModal = () => {
      showAddModal.value = true
      editingProduct.value = null
      productForm.value = {
        name: '',
        description: '',
        price: '',
        category: '',
        stock: '',
        image: null
      }
      imagePreview.value = null
    }

    const openEditModal = (product) => {
      editingProduct.value = product
      productForm.value = {
        name: product.name,
        description: product.description,
        price: product.price,
        category: product.category,
        stock: product.stock,
        image: null
      }
      imagePreview.value = null
      showAddModal.value = true
    }

    const handleSubmit = async () => {
      try {
        const formData = new FormData()
        
        // Append basic fields
        formData.append('name', productForm.value.name)
        formData.append('description', productForm.value.description)
        formData.append('price', productForm.value.price)
        formData.append('category', productForm.value.category)
        formData.append('stock', productForm.value.stock)
        
        // Handle image file - only append if new image is selected
        const fileInput = document.querySelector('input[type="file"]')
        if (fileInput && fileInput.files[0]) {
          formData.append('image', fileInput.files[0])
        }

        let response
        if (editingProduct.value) {
          formData.append('_method', 'PUT') 
          response = await api.post(`/products/${editingProduct.value.id}`, formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            }
          })
        } else {
          response = await api.post('/products', formData, {
            headers: {
              'Content-Type': 'multipart/form-data',
            }
          })
        }

        if (response.data.status === 'success' || response.status === 200) {
          closeModal()
          await loadProducts()
        }
      } catch (error) {
        console.error('Error saving product:', error.response?.data || error)
      }
    }

    const closeModal = () => {
      showAddModal.value = false
      editingProduct.value = null
      productForm.value = {
        name: '',
        description: '',
        price: '',
        category: '',
        stock: '',
        image: null
      }
      imagePreview.value = null
    }

    const handleImageChange = (event) => {
      const file = event.target.files[0]
      if (file) {
        // Validate file type
        if (!file.type.startsWith('image/')) {
          alert('Please select an image file')
          event.target.value = ''
          return
        }
        
        // Validate file size (e.g., 5MB limit)
        const maxSize = 5 * 1024 * 1024 // 5MB in bytes
        if (file.size > maxSize) {
          alert('Image size should be less than 5MB')
          event.target.value = ''
          return
        }

        // Store the raw File object
        productForm.value.image = file
        imagePreview.value = URL.createObjectURL(file)
      }
    }

    const deleteProduct = async (product) => {
      if (confirm('Are you sure you want to delete this product?')) {
        try {
          await api.delete(`/products/${product.id}`)
          loadProducts()
        } catch (error) {
          console.error('Error deleting product:', error)
        }
      }
    }

    const changePage = (page) => {
      currentPage.value = page
      loadProducts()
    }

    const debounceSearch = debounce(() => {
      loadProducts()
    }, 300)

    onMounted(loadProducts)

    return {
      products,
      filters,
      currentPage,
      totalPages,
      showAddModal,
      editingProduct,
      productForm,
      imagePreview,
      loadProducts,
      handleSubmit,
      closeModal,
      handleImageChange,
      deleteProduct,
      imageUrl,
      openAddModal,
      openEditModal,
      changePage,
      debounceSearch,
      categories
    }
  }
}
</script>

<style scoped>
.products-page {
  padding: 1rem;
}

.product-image {
  height: 200px;
  object-fit: cover;
}

.modal {
  background-color: rgba(0, 0, 0, 0.5);
}

.price {
  font-size: 1.25rem;
  font-weight: 600;
  color: #2c3e50;
}

.stock {
  color: #6c757d;
}
</style>