# Larafony.com - Official Website & Documentation

The official landing page and documentation for [Larafony Framework](https://github.com/DJWeb-Damian-Jozwiak/larafony) - built **with** Larafony itself! 🚀

## 🌐 Live Site

**https://larafony.com**

Experience a modern PHP 8.5 framework in action. This website showcases Larafony's capabilities while serving as comprehensive documentation for developers.

## 📖 What's Inside

- **🎨 Landing Page** - Modern dark-themed homepage with gradient animations
- **📚 Interactive Documentation** - Complete guides covering:
  - Controllers & Routing (with model binding)
  - Models & Relationships (BelongsTo, HasMany, BelongsToMany)
  - DTO Validation (all 13 attributes + PHP 8.5 features)
  - Middleware (PSR-15)
  - Application Bootstrap
  - Project Structure
- **💡 Real-World Example** - This site demonstrates Larafony in production

## ⚡ Tech Stack

- **[Larafony Framework](https://github.com/DJWeb-Damian-Jozwiak/larafony)** - Modern PHP 8.5 framework
- **Blade Templates** - Custom components with slots
- **PSR-7 HTTP** - Request/Response handling
- **PSR-15 Middleware** - Clean middleware stack
- **Prism.js** - Beautiful syntax highlighting (Tomorrow Night theme)
- **Bootstrap 5** - Responsive UI framework

## ✨ Features Demonstrated

This website showcases real-world usage of Larafony features:

✅ **Attribute-based Routing** - `#[Route]` attributes on controllers
✅ **Model Binding** - `#[RouteParam]` for automatic model resolution
✅ **Blade Components** - Reusable components with `<x-layout>` and `<x-docs-layout>`
✅ **Custom Architecture** - Organized controllers, views, and components
✅ **SEO Optimization** - Meta tags, Open Graph, Twitter Cards
✅ **PSR-15 Middleware** - Custom middleware pipeline

## 🚀 Local Development

### Requirements
- PHP ≥ 8.5
- Composer
- MySQL/PostgreSQL/SQLite (for demo database)

### Installation

```bash
# Clone the repository
git clone https://github.com/DJWeb-Damian-Jozwiak/larafony-website
cd larafony-website

# Install dependencies
composer install

# Copy environment file
cp .env.example .env

# Configure your database in .env
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_DATABASE=larafony_com
# DB_USERNAME=your_username
# DB_PASSWORD=your_password

# Start development server
php8.5 -S localhost:8000 -t public
```

Visit http://localhost:8000 🎉

## 📂 Project Structure

```
larafony.com/
├── bootstrap/          # Application bootstrap files
├── config/            # Configuration files
├── public/            # Public web directory
│   ├── index.php     # Entry point
│   └── logo.png      # Framework logo
├── resources/
│   └── views/
│       └── blade/    # Blade templates
│           ├── components/   # Reusable components
│           ├── docs/         # Documentation pages
│           └── home.blade.php
├── src/
│   ├── Controllers/  # Route controllers
│   └── View/         # View components
└── storage/          # Logs and cache
```

## 🎯 Key Files

### Controllers
- `src/Controllers/HomeController.php` - Landing page
- `src/Controllers/DocsController.php` - Documentation pages (7 routes)

### Blade Components
- `resources/views/blade/components/Layout.blade.php` - Main layout
- `resources/views/blade/components/DocsLayout.blade.php` - Documentation layout

### Documentation Pages
- `/docs` - Introduction
- `/docs/structure` - Project structure
- `/docs/models` - Models & relationships
- `/docs/controllers` - Controllers & routing with model binding
- `/docs/validation` - DTO validation (13 attributes)
- `/docs/middleware` - PSR-15 middleware
- `/docs/bootstrap` - Application bootstrap

## 🤝 Contributing

Found a typo in the documentation? Broken link? UI improvement idea?

We welcome contributions! Feel free to:
- 🐛 Report issues
- 📝 Fix documentation typos
- 💡 Suggest improvements
- 🎨 Improve UI/UX

### How to Contribute

1. Fork the repository
2. Create your feature branch (`git checkout -b fix/typo-in-docs`)
3. Commit your changes (`git commit -m 'Fix typo in validation docs'`)
4. Push to the branch (`git push origin fix/typo-in-docs`)
5. Open a Pull Request

## 🔗 Related Projects

- **[Larafony Framework](https://github.com/DJWeb-Damian-Jozwiak/larafony)** - The core framework
- **[Larafony Demo App](https://github.com/DJWeb-Damian-Jozwiak/larafony-demo-app)** - Simple notes application demo

## 📚 Learn More

- 🌐 **Website**: https://larafony.com
- 📖 **Documentation**: https://larafony.com/docs
- 🎓 **Learn How It's Built**: https://masterphp.eu
- 📦 **Packagist**: https://packagist.org/packages/larafony/core

## 📄 License

MIT License - see [LICENSE](LICENSE) file for details.

## 💪 Built With Love

This website is built using Larafony Framework and serves as both documentation and a real-world example of the framework's capabilities.

**Built with ❤️ using Larafony Framework + PHP 8.5**

---

© 2025 Larafony Framework. Open source and free forever.
