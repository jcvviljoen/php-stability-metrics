# Stability
#### PHP Stable Dependency Metrics Analyser

## Overview

Stability is a PHP-based tool designed to analyse and calculate the stability of software components.
It leverages clean architecture principles and stable dependency metrics to provide insights into the maintainability and robustness of your codebase.

## Installation

To install the project, use Composer:

```bash
composer require jcvviljoen/stability
```

## Usage

To calculate the stability of your components, run the following command:

```bash
php vendor/bin/stability
```

Various arguments are also supported (don't worry, any invalid setup will guide you through the process anyway):

```
-i, --init  => Initialize the configuration file
--config    => Specify a custom configuration file / path
--debug     => Enable debug output (exposes exception stack traces)
```

For example, you can specify a custom configuration file (as long as it is a supported format):

```bash
php vendor/bin/stability --config "path/to/config.php"
```

## Features

- **Component Parsing**: Parses class and modules into components as specified by your configuration.
- **Stability Calculation**: Computes metrics such as abstractness, instability, and distance from the main sequence (DMS).
- **Output Results**: Outputs the calculated stability results for further analysis.

## Clean Architecture Principles

The project tries to showcase and promote the understanding of the following principles:

**Placeholder** I want to elaborate on component cohesion & coupling, SOLID principles, and the importance of clean architecture.

## Stable Dependency Metrics

Stability uses the following metrics to evaluate the stability of components:

- **Abstractness (A)**: Measures the ratio of abstract classes and interfaces to the total number of classes. A higher value indicates more abstract components.
- **Instability (I)**: Measures the ratio of outgoing dependencies to the total number of dependencies. A higher 
  value indicates more unstable components (i.e. components that are hard to change due to their high number of dependencies).
- **Distance from the Main Sequence (DMS)**: Combines abstractness and instability to determine how far a component is from the ideal balance of being abstract and stable.


## Contributing

If you would like to contribute to the project, please follow the steps below:

1. Fork the repository.
2. Make the necessary changes (remember to write tests!). 
3. Commit your changes (`git commit -am 'Add new feature'`). 
4. Create a new Pull Request. 
5. Wait for feedback and approval. 
6. Once approved, the changes will be merged. 
7. Celebrate your contribution! 🎉

### New plugins

The project is designed to be extensible, so you can create new plugins to add more functionality.

For example, if you have a specific configuration file that you'd like to use,
you can create a new `ConfigLoader` to support it!

### Testing

To run the tests, use the following command:

```bash
composer tests
```

## License

This project is licensed under the MIT License — see the [LICENSE](LICENSE) file for details.
