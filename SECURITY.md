### 🔒 **SECURITY POLICY** 🔒  
*Effective Date: March 24, 2025*  
*Repository: [Yui Installer](https://github.com/luis-developer-08/yui-installer)*  

---

### 🚨 **Reporting a Vulnerability**

If you discover a **security vulnerability** in this project, please follow these steps:  

1. **Do NOT open a public issue.**  
   - Instead, report the vulnerability by emailing:  
   📧 **sandyluisbalbuena.business@gmail.com**  
   - Use the subject line:  
   ```
   [SECURITY] Vulnerability Report - Yui Installer
   ```

2. **Provide the following details in your report:**
   - A detailed description of the vulnerability.  
   - Steps to reproduce the issue (proof of concept is helpful).  
   - Potential impact of the vulnerability.  
   - Suggested fix or mitigation (if available).  

3. **Expected Response Time:**  
   - We will acknowledge your report within **48 hours**.  
   - You will receive regular updates on the status of the issue.  
   - A fix will be released promptly, depending on the severity of the vulnerability.  

---

### 🔥 **Supported Versions**

We actively maintain the following versions of **Yui Installer**. Security patches will be applied to these versions:  

| Version       | Supported           | Notes                  |
|---------------|----------------------|------------------------|
| `v1.0.3`      | ✅ Actively supported | Current stable version |
| `v1.0.x`      | ✅ Supported          | Minor patches & fixes  |
| `< v1.0.0`    | ❌ No longer supported | Upgrade recommended   |

---

### 🔐 **Security Best Practices**

To help maintain the security of your **Yui Installer** installation:  

1. **Keep dependencies up to date:**  
   - Regularly run:  
   ```bash
   composer update
   npm update
   ```

2. **Use secure configurations:**  
   - Ensure your `.env` file contains proper production values.  
   - Use strong encryption keys and never expose them publicly.  
   - Always set the `APP_DEBUG` flag to `false` in production.  

3. **Use HTTPS:**  
   - Always use `HTTPS` in production to encrypt sensitive data.  

4. **Rate Limiting & CSRF Protection:**  
   - Use Laravel’s built-in **CSRF protection**.  
   - Implement **rate limiting** on sensitive API routes.  

---

### 🔍 **Disclosure Policy**

- We follow a **responsible disclosure** model.  
- If you report a security issue, you will be credited after it has been resolved (if you wish).  
- We may publicly disclose the vulnerability once a patch has been released.  

---

### 💙 **Thank You**

We greatly appreciate your efforts in keeping **Yui Installer** secure. Your contributions help us create a safer environment for all users. 🚀
