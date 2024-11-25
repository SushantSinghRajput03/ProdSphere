# ProdSphere
A dynamic platform for efficient product management. Effortlessly add, update, and delete products. Leveraging Laravel 11 and Vue.js 3, ProdSphere ensures a seamless user experience, prioritizing security, scalability, and performance.

## Features

- **Product CRUD:**
  - Create, read, update and delete products with ease
  - Upload product images with automatic storage management
  - Filter products by name, category, and price range
  - Sort products by various attributes (name, price, date)
  - Paginated product listing for better performance
  - Real-time image preview when adding/editing products

- **User Authentication:**
  - Secure user registration and login system
  - JWT-based authentication for API endpoints
  - Protected routes and product ownership validation
  - User-specific product management

- **Validation:**
  - Robust client-side and server-side validations for all inputs, ensuring accurate and secure data.


## Technologies Used / Requirement

- Laravel 11.31
- Vue 3.5.12
- Node 20.12.1
- npm 10.5.0
- PHP 8.2
- MySql

## Setup Instructions

Follow these steps to set up the project locally:

1. **Clone the Repository**
   ```bash
   git clone https://github.com/SushantSinghRajput03/ProdSphere.git
   cd ProdSphere
   ```
2. **Backend Setup**
   ```bash
   # Navigate to backend directory
   cd ProdSphere-be

   # Install PHP dependencies
   composer install

   # Set up environment file
   cp .env.example .env

   # Configure your database settings in .env file
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=prodsphere
   DB_USERNAME=root
   DB_PASSWORD=

   # Generate application key
   php artisan key:generate

   # Run database migrations
   php artisan migrate

   # Create storage symbolic link
   php artisan storage:link
   ```

3. **Frontend Setup**
   ```bash
   # Navigate to frontend directory
   cd ../ProdSphere-fe

   # Install dependencies
   npm install
   ```
   # Set up environment file
   cp .env.example .env
   
   # Update the VITE_API_URL in .env file to match your backend URL
   # Example: VITE_API_URL=http://localhost:8000

4. **Start Development Servers**
   ```bash
   # Start Backend Server (from ProdSphere-be directory)
   php artisan serve   # Runs on http://localhost:8000

   # Start Frontend Dev Server (from ProdSphere-fe directory)
   npm run dev        # Runs on http://localhost:5173
   
   # For production build
   npm run build
   ```

   Your application will be available at:
   - Frontend: `http://localhost:5173`
   - Backend API: `http://localhost:8000`

## Contributing

Please read [CONTRIBUTING.md](CONTRIBUTING.md) for details on our code of conduct and the process for submitting pull requests.

## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details.