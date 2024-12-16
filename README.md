# 🚀 WoxupChat - Modern WordPress WhatsApp Contact Form

![WoxupChat Banner](assets/images/banner.png)

[![WordPress Version](https://img.shields.io/wordpress/plugin/v/woxupchat.svg)](https://wordpress.org/plugins/woxupchat/)
[![WordPress Rating](https://img.shields.io/wordpress/plugin/rating/woxupchat.svg)](https://wordpress.org/support/plugin/woxupchat/reviews/)
[![Downloads](https://img.shields.io/wordpress/plugin/dt/woxupchat.svg)](https://wordpress.org/plugins/woxupchat/)

WoxupChat is an elegant and modern WordPress plugin that seamlessly integrates WhatsApp messaging into your contact forms. Transform your website's communication by allowing visitors to reach you directly through WhatsApp while maintaining a professional and user-friendly interface.

## ✨ Features

- 🎨 **Modern UI/UX Design**: Sleek, responsive interface that works beautifully on all devices
- 💬 **WhatsApp Integration**: Direct messaging through WhatsApp with pre-filled messages
- 🔒 **Secure Form Handling**: Built with WordPress security best practices
- 📝 **Form Validation**: Client and server-side validation for reliable data collection
- 📱 **Mobile-First Design**: Optimized for both desktop and mobile experiences
- 📊 **Logging System**: Track form submissions and troubleshoot with ease
- ⚡ **AJAX Submission**: Smooth, asynchronous form submissions without page reloads
- 🎯 **Easy Integration**: Simple shortcode implementation

## 🚀 Quick Start

### Installation

1. Download the plugin from WordPress.org or clone this repository
2. Upload to your WordPress plugins directory
3. Activate the plugin through WordPress admin panel

### Basic Usage

Add the contact form to any page or post using the shortcode:
```
[woxupchat_form]
```

### Configuration

1. Navigate to **Settings > WoxupChat** in your WordPress admin panel
2. Enter your WhatsApp number
3. Customize form fields and messages
4. Save your settings

## 🎨 Customization

### CSS Variables

WoxupChat uses CSS custom properties for easy theming:

```css
:root {
    --woxupchat-primary-color: #25D366;
    --woxupchat-text-color: #333333;
    --woxupchat-bg-color: #ffffff;
    /* ... more variables available */
}
```

### Hooks and Filters

Extend functionality using WordPress hooks:

```php
// Modify form fields
add_filter('woxupchat_form_fields', 'custom_form_fields');

// Custom validation
add_filter('woxupchat_validate_form', 'custom_validation');
```

## 📝 Documentation

For detailed documentation, visit our [Wiki](https://github.com/yourusername/woxupchat/wiki).

## 🤝 Contributing

We welcome contributions! Please feel free to submit a Pull Request.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📜 License

This project is licensed under the GPL v2 or later - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- Built with love for the WordPress community
- WhatsApp is a trademark of Meta Platforms, Inc.

## 📧 Support

Need help? Check out our [support forum](https://wordpress.org/support/plugin/woxupchat/) or [create an issue](https://github.com/yourusername/woxupchat/issues).

---

<p align="center">
Made with ❤️ by <a href="https://yourwebsite.com">Your Name</a>
</p>
