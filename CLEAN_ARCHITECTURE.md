## Clean Architecture Principles

The project tries to showcase and promote the understanding of the following principles:

### Component Cohesion

**Cohesion** refers to how closely related the responsibilities of a single module are.

There are a few principles that are important here:

#### 1. Reuse Release Equivalence Principle (REP)

> The granule of reuse is the granule of release.

The REP states that classes that are used together should be released together.

#### 2. Common Closure Principle (CCP)

> Classes that change together are packaged together.

The CCP states that classes that are likely to change together should be packaged together.

#### 3. Common Reuse Principle (CRP)

In contrast to the previous principles (which are about classes that are used together),
the CRP states that classes that are not used together **should not** be packaged together.

---

### Component Coupling

**Coupling** refers to how dependent modules are on each other.

There are a few principles that are also important here:

#### 1. Acyclic Component Dependencies Principle (ADP)

> Allow no cycles in the component dependency graph.

The ADP states that the dependency graph should be acyclic.
This is important to prevent circular dependencies—which can make the system hard to understand and maintain.

#### 2. Stable Dependencies Principle (SDP)

> Depend in the direction of stability.

The SDP states that components should depend on other components that are more stable than themselves.

#### 3. Stable Abstractions Principle (SAP)

> A component should be as abstract as it is stable.

The SAP states that components should be as abstract as they are stable.
This means that stable components should be more abstract, while unstable components should be more concrete.

**_High cohesion_** and **_low coupling_** are desired for maintainable and scalable systems.

---

#### SOLID Principles

The SOLID principles are only mentioned here due to their importance in clean architecture. A lot of the principles
mentioned above are derived from these principles.

**Note: The SOLID principles are important for writing clean code,
whereas the component principles are important for designing clean architecture.**

- **Single Responsibility Principle (SRP)**: A class should have only one reason to change, meaning it should have only one job or responsibility.
- **Open/Closed Principle (OCP)**: Software entities should be open for extension but closed for modification. This means you should be able to add new functionality without changing existing code.
- **Liskov Substitution Principle (LSP)**: Objects of a superclass should be replaceable with objects of a subclass without affecting the correctness of the program.
- **Interface Segregation Principle (ISP)**: Clients should not be forced to depend on interfaces they do not use. Instead, many client-specific interfaces are better than one general-purpose interface.
- **Dependency Inversion Principle (DIP)**: High-level modules should not depend on low-level modules. Both should depend on abstractions. Abstractions should not depend on details. Details should depend on abstractions.

___

### Importance of Clean Architecture

Clean architecture emphasises the separation of concerns, making the system easier to understand, develop, and maintain.
It promotes the use of layers, where each layer has a specific responsibility and communicates with other layers
through well-defined interfaces.

This approach helps in creating systems that are flexible, testable, and scalable.

## Stable Dependency Metrics

Stability uses the following metrics to evaluate the stability of components:

- **Abstractness (A)**: Measures the ratio of abstract classes and interfaces to the total number of classes. A higher value indicates more abstract components.
- **Instability (I)**: Measures the ratio of outgoing dependencies to the total number of dependencies. A higher
  value indicates more unstable components (i.e. components that are hard to change due to their high number of dependencies).
- **Distance from the Main Sequence (DMS)**: Combines abstractness and instability to determine how far a component is from the ideal balance of being abstract and stable.
