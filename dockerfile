# Use the official PHP image
FROM php:8.2-cli

# Create and set working directory
WORKDIR /app

# Copy everything from repo into container
COPY . /app

# Expose port for Render
EXPOSE 10000

# Start PHP built-in server from inside /app
CMD ["php", "-S", "0.0.0.0:10000", "/app/index.php"]
