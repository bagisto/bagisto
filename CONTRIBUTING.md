# Bagisto Contribution Guide

**BUGS:**

At Bagisto, we highly value active collaboration among our community members to continually enhance our platform's performance and reliability. To facilitate this collaborative effort, we extend a warm invitation to both report bugs and submit pull requests.

Rather than solely reporting bugs, we encourage you to take an active role in resolving issues by submitting pull requests containing fixes or negative test cases that effectively highlight the problem. This approach not only identifies issues but also provides practical solutions for their resolution.

When filing a bug report, we kindly request you to include a clear and descriptive title, along with a detailed description of the encountered problem. Additionally, please provide as much relevant information as possible, including a code sample that can reproduce the bug. Such comprehensive reports significantly expedite the troubleshooting process and enable swift resolution.

It is our collective goal to foster collaboration and find effective solutions to the challenges encountered. By actively participating in bug reporting, you not only engage fellow community members in problem-solving but also contribute significantly to the ongoing enhancement of the Bagisto project.

**Core development ideas or discussion:**

If you propose a new feature, please implement at least some of the code needed to complete the quality.

Informal discussion regarding bugs, new features, and implementation of existing features occurs in the comments of the issues filed using feature template.

**Which branch you should target?**

All bug fixes should be sent to the latest staging branch, i.e. development branch. Bug fixes should never be sent to the master branch unless they fix features that exist only in the upcoming release.

Minor features fully backwards compatible with the current Laravel release may be sent to the latest stable branch.

Major new features should always be sent to the master branch, which contains the upcoming Bagisto release.

**Compiling assets:**

To determine the sorting order for Tailwind CSS classes, consult the official Tailwind CSS documentation for guidelines on class organization. Additionally, consider using the Tailwind Raw Reorder extension for VS Code to streamline the sorting process.

**Code style:**

Bagisto follows PSR-2 for coding standards and PSR-4 as of Laravel for autoloading standards.

**PHPDoc:**

Below is an example of a valid Bagisto doc block. Note that the @param attribute is followed by two spaces, the argument type, two more spaces, and finally, the variable name:
  ``` php
    /**
    * Register a service with CoreServiceProvider.
    *
    * @param  string|array  $loader
    * @param  \Closure|string|null  $concrete
    * @param  bool  $shared
    */
    protected function registerFacades($loader, $concrete = null, $shared = false): void
    {
        //
    }
  ```
