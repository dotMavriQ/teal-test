      ████████╗███████╗ █████╗ ██╗     
      ╚══██╔══╝██╔════╝██╔══██╗██║     
         ██║   █████╗  ███████║██║     
         ██║   ██╔══╝  ██╔══██║██║     
         ██║   ███████╗██║  ██║███████╗
         ╚═╝   ╚══════╝╚═╝  ╚═╝╚══════╝

## Introduction

Welcome to Teal-test, the genesis of the Teal project. Teal is designed to be a unified space where users can manage and review their collections of books, games, movies, and music. Gone are the days when you needed multiple services to keep track of all your favorite content. Teal aims to be a singular, streamlined solution for all your collection needs.

## Pre-Alpha State

As Teal is in its pre-alpha stage, it currently supports importing book collections from GoodReads in CSV format. The imported data is converted into a JSON file, and Teal will attempt to scrape the appropriate cover art for each entry.

Please be advised that features are very limited during this pre-alpha stage and may be subject to change.

## Future Plans

- **Import Support for Multiple Platforms**: Teal aims to support imports from platforms like MobyGames, Steam, GOG, itch.io for games, IMDb for movies, and Discogs for music collections.

- **Local Instance**: Users will be able to run their own local instance of Teal, thus having total control over their data.

- **Various Interfaces**: Teal is planned to be available in different forms such as a web app, desktop app, or even as a terminal program for the tech-savvy users.

- **Metadata Acquisition**: The goal is to make Teal capable of leveraging metadata from various platforms for the enrichment of the collections without being dependent on them.

## Getting Started

### Prerequisites

As Teal is in pre-alpha, the prerequisites are subject to change. Currently, you will need:

- Python 3.x
- Basic knowledge of terminal commands

### Installation

1. Clone the repository:
   ```sh
   git clone https://github.com/dotmavriq/teal-test.git
   cd teal-test
   ```

2. Install the required packages:
   ```sh
   pip install locale collections time selenium.webdriver.support.ui csv json selenium selenium.webdriver.support os ast selenium.webdriver.common.by bs4
   ```

3. Visit [https://www.goodreads.com/review/import](https://www.goodreads.com/review/import)

   Press the `Export` button, wait a painstaking moment, update the page, press the newly generated link to your export, download `goodreads_library_export.csv`

5. First, we convert the CSV into a `.json` file named `output.json`:
   ```sh
   python teal.py
   ```

6. Now we want to inject cover art information for all entries. This is currently done by `testing.py`:
   ```sh
   python testing.py
   ```

7. Use python to host the HTML file locally:
   ```sh
   python -m http.server
   ```

8. [OPTIONAL] For the hell of it, there is also `pages.py` which returns the books from "to-read" with the least amount of numbers of pages. This is mainly to coax me into reading more. So this is optional.
   ```sh
   python pages.py
   ```

## Contributing

Teal is an open-source project, and contributions are welcome! If you have ideas or code that could enhance the app, please consider contributing.

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/YourFeature`)
3. Commit your changes (`git commit -m 'Add some feature'`)
4. Push to the branch (`git push origin feature/YourFeature`)
5. Open a pull request

## License

Teal-test is released under the MIT License. See `LICENSE` for more information.

## Contact

dotMavriQ - [Mail](dotmavriq@dotmavriq.life) 

Project Link: [Teal-test](https://github.com/dotMavriQ/teal-test/)

## Acknowledgments

- LibreReads for inspiration
- All the platforms that we aim to support in the future

Please note that Teal-test is in its early stages, and we appreciate your patience and support. If you have any feedback or suggestions, please feel free to reach out or contribute.
