# php-stability-metrics
This project is dedicated to calculating stability metrics for components in PHP projects.

## Installation
To install required dependencies, run the following command:
```bash
composer install
```

## Inspiration
In Uncle Bob's book **_Clean Architecture_**, he introduces the concept of **Stability Metrics**.

These metrics are used to determine the stability of a component in a software project.
The stability of a component is determined by the number of incoming and outgoing dependencies.
The more incoming dependencies a component has, the more stable it is.
The more outgoing dependencies a component has, the more unstable it is.

This all plays into the concept of **Component Cohesion Principles** which are:
1. **Reuse / Release Equivalence Principle (REP)**: The granule of reuse is the granule of release.
2. **Common Closure Principle (CCP)**: Classes that change together are packaged together.
3. **Common Reuse Principle (CRP)**: Classes that are used together are packaged together.

These principles can be summarised in the following **Tension Diagram**:
