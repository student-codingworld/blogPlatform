# Security Policy

Thank you for helping secure the PHP Blogging Website project. We take all security vulnerabilities seriously and aim to address them promptly.

---

## 📌 Supported Versions

We only provide security updates for the latest stable versions:

| Version | Supported          |
|---------|--------------------|
| 1.2.x   | ✅ Supported        |
| 1.1.x   | ✅ Supported        |
| 1.0.x   | ❌ Deprecated       |
| < 1.0   | ❌ Not Supported    |

---

## 🔐 Reporting a Vulnerability

If you discover a security vulnerability in this project, **please do not open a public issue**.

### Instead, please:

- Email us directly at **security@myblog.com** (replace with your real email)
- Include a detailed description and, if possible, steps to reproduce
- Attach sample payloads, affected files, or screenshots (if applicable)

---

## ⏱️ Response Expectations

| Step                  | Timeline                        |
|-----------------------|----------------------------------|
| Initial Acknowledgment | Within 24–48 hours              |
| Triage & Validation   | Within 3–5 business days         |
| Patch Release Target  | Within 7–14 business days        |
| Public Disclosure     | After patch or by mutual consent |

---

## 🔒 Scope of Supported Issues

We are committed to fixing vulnerabilities related to:

- Authentication bypass
- SQL Injection
- Cross-Site Scripting (XSS)
- Session Hijacking
- Privilege Escalation
- File Upload Exploits

---

## ❌ Out of Scope

We will not address:

- Bugs in deprecated versions
- Non-security related UX bugs
- Issues with third-party libraries unless integrated directly

---

## ✅ Best Practices We Follow

- All passwords hashed using `password_hash()`
- PDO prepared statements used to prevent SQL injection
- CSRF and XSS protections in progress
- Session validation for all restricted panels

---

## 📃 Legal Disclaimer

This policy does not grant you permission to test this software in production environments that you do not own or have permission to test. Please conduct ethical research only.

---

Thank you for helping us keep the PHP Blogging Website secure! 🙏
