### 🛠️ **CONTRIBUTING GUIDELINES** 🛠️  
*Welcome to the **Yui Installer** repository! 🎉 We’re excited to have you contribute and help improve this project. To maintain a healthy and collaborative environment, please follow the guidelines below.*  

---

### 🚀 **How to Contribute**

1. **Fork the Repository**
   - Click the **Fork** button in the top-right corner.
   - Clone your fork locally:
   ```bash
   git clone https://github.com/your-username/yui-installer.git
   cd yui-installer
   ```

2. **Create a New Branch**
   - Use a descriptive branch name:
   ```bash
   git checkout -b feature/your-feature-name
   ```

3. **Install Dependencies**
   - Install PHP and JS dependencies:
   ```bash
   composer install
   npm install
   ```

4. **Make Your Changes**
   - Follow the **code style** and **naming conventions**.  
   - Write clean, readable, and maintainable code.  
   - Include **tests** if applicable.  

5. **Commit Your Changes**
   - Write clear, concise, and meaningful commit messages:
   ```bash
   git add .
   git commit -m "feat: add support for XYZ feature"
   ```
   ✅ **Conventional commit format:**  
   ```
   feat:     A new feature  
   fix:      A bug fix  
   docs:     Documentation changes  
   style:    Code style changes (formatting, missing semi-colons)  
   refactor: Code refactoring (no new features or fixes)  
   test:     Adding or updating tests  
   chore:    Minor changes (build, CI, etc.)  
   ```

6. **Push Your Changes**
   - Push your branch to your forked repository:
   ```bash
   git push origin feature/your-feature-name
   ```

7. **Submit a Pull Request**
   - Go to the original repo and click **"New Pull Request"**.  
   - Provide a **detailed description** of the changes:
     - What problem does it solve?  
     - How did you solve it?  
     - Include screenshots, if applicable.  
   - Reference any related issues using:  
   ```markdown
   Closes #123
   ```

---

### ✅ **Code Style & Best Practices**

1. **PHP:**
   - Follow **PSR-12** coding standards.  
   - Use **type hinting** and strict typing where possible.  
   - Add PHPDoc comments for complex functions and classes.  
   - Properly format SQL queries in Eloquent.  
   
2. **React (Frontend):**
   - Follow **React best practices** with hooks and functional components.  
   - Use **Tailwind CSS** classes consistently.  
   - Use **React Query** for data fetching.  
   - Ensure your code is modular and reusable.  

3. **Naming Conventions:**
   - Use `camelCase` for variables and functions.  
   - Use `PascalCase` for React components and classes.  
   - Use `snake_case` for database table and column names.  

---

### 🔥 **Testing**

1. **Backend (PHP/Laravel):**
   - Write **unit tests** using PHPUnit.  
   - Run tests with:
   ```bash
   php artisan test
   ```

2. **Frontend (React):**
   - Add component and integration tests.  
   - Use `Jest` or `React Testing Library`.  
   - Run tests with:
   ```bash
   npm run test
   ```

---

### 🔥 **Issue Reporting Guidelines**

1. **Check for existing issues** before opening a new one.  
2. **Provide detailed information:**
   - Describe the problem clearly.  
   - Add steps to reproduce the issue.  
   - Include relevant screenshots, logs, or code snippets.  
3. **Label the issue properly:**
   - `bug`: For bugs and issues.  
   - `enhancement`: For new features or improvements.  
   - `documentation`: For issues related to docs.  

---

### 🌟 **Pull Request Guidelines**

1. **Small, focused PRs:**  
   - Keep pull requests small and specific to a feature or bug fix.  
   - Avoid combining unrelated changes into a single PR.  

2. **PR Review Process:**  
   - Be responsive to feedback.  
   - Make requested changes promptly.  
   - Ensure the PR passes all tests and CI checks.  

---

### 💬 **Communication**

- Use **clear and respectful language** in issues, PRs, and comments.  
- Ask for clarification if you are unsure about a feature or issue.  
- Feel free to discuss larger features or architecture changes before implementing them.  

---

### 📚 **Resources**

- [Laravel Documentation](https://laravel.com/docs)  
- [React Documentation](https://reactjs.org/docs/getting-started.html)  
- [PSR-12 Coding Standard](https://www.php-fig.org/psr/psr-12/)  
- [React Query](https://tanstack.com/query/v4/docs/react/overview)  

---

### 💙 **Thank You for Contributing!**
*Your contributions help make Yui Installer better for everyone. 🎯 Happy coding!* 🚀
