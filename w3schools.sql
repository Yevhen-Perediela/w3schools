-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 06, 2025 at 07:20 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `w3schools`
--

-- --------------------------------------------------------

--
-- Table structure for table `contact`
--

CREATE TABLE `contact` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp(),
  `topic` varchar(255) NOT NULL DEFAULT 'Inne'
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;


-- --------------------------------------------------------

--
-- Table structure for table `kursy`
--

CREATE TABLE `kursy` (
  `id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `kurs_type` varchar(255) NOT NULL,
  `kurs_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin NOT NULL CHECK (json_valid(`kurs_data`)),
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Dumping data for table `kursy`
--

INSERT INTO `kursy` (`id`, `title`, `kurs_type`, `kurs_data`, `created_at`) VALUES
(7, 'Zmienne', 'HTML', '\"[\\n  {\\n    \\\"type\\\": \\\"h2\\\",\\n    \\\"content\\\": \\\"Hello world\\\",\\n    \\\"styles\\\": {\\n      \\\"class\\\": \\\"header-input h2\\\"\\n    }\\n  },\\n  {\\n    \\\"type\\\": \\\"image-container\\\",\\n    \\\"images\\\": [\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/uploads\\/677b0e4d3012c_3d-music-related-scene.jpg\\\",\\n        \\\"width\\\": \\\"272px\\\",\\n        \\\"height\\\": \\\"272px\\\"\\n      },\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/assets\\/img\\/resize.png\\\",\\n        \\\"width\\\": \\\"auto\\\",\\n        \\\"height\\\": \\\"auto\\\"\\n      }\\n    ]\\n  },\\n  {\\n    \\\"type\\\": \\\"h3\\\",\\n    \\\"content\\\": \\\"Co\\u015b...\\\",\\n    \\\"styles\\\": {\\n      \\\"class\\\": \\\"header-input h3\\\"\\n    }\\n  },\\n  {\\n    \\\"type\\\": \\\"textarea\\\",\\n    \\\"content\\\": \\\"jkejkekfjkejfkjekfjekjfkjefj\\\",\\n    \\\"styles\\\": {\\n      \\\"height\\\": \\\"30px\\\"\\n    }\\n  },\\n  {\\n    \\\"type\\\": \\\"image-container\\\",\\n    \\\"images\\\": [\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/uploads\\/677b0e66c0836_playlist2.jpeg\\\",\\n        \\\"width\\\": \\\"167px\\\",\\n        \\\"height\\\": \\\"167px\\\"\\n      },\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/assets\\/img\\/resize.png\\\",\\n        \\\"width\\\": \\\"auto\\\",\\n        \\\"height\\\": \\\"auto\\\"\\n      },\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/uploads\\/677b0e66c099d_pxfuel.jpg\\\",\\n        \\\"width\\\": \\\"auto\\\",\\n        \\\"height\\\": \\\"auto\\\"\\n      },\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/assets\\/img\\/resize.png\\\",\\n        \\\"width\\\": \\\"auto\\\",\\n        \\\"height\\\": \\\"auto\\\"\\n      }\\n    ]\\n  }\\n]\"', '2025-01-05 23:57:53'),
(8, 'Loop (for, while)', 'JS', '\"[\\n  {\\n    \\\"type\\\": \\\"h2\\\",\\n    \\\"content\\\": \\\"Hello world 2\\\",\\n    \\\"styles\\\": {\\n      \\\"class\\\": \\\"header-input h2\\\"\\n    }\\n  },\\n  {\\n    \\\"type\\\": \\\"image-container\\\",\\n    \\\"images\\\": [\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/uploads\\/677b0e4d3012c_3d-music-related-scene.jpg\\\",\\n        \\\"width\\\": \\\"128px\\\",\\n        \\\"height\\\": \\\"128px\\\"\\n      },\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/assets\\/img\\/resize.png\\\",\\n        \\\"width\\\": \\\"auto\\\",\\n        \\\"height\\\": \\\"auto\\\"\\n      }\\n    ]\\n  },\\n  {\\n    \\\"type\\\": \\\"textarea\\\",\\n    \\\"content\\\": \\\"snddnedwded\\\",\\n    \\\"styles\\\": {\\n      \\\"height\\\": \\\"30px\\\"\\n    }\\n  },\\n  {\\n    \\\"type\\\": \\\"h3\\\",\\n    \\\"content\\\": \\\"Co\\u015b...sncncjdncjncjndcndcn\\\",\\n    \\\"styles\\\": {\\n      \\\"class\\\": \\\"header-input h3\\\"\\n    }\\n  },\\n  {\\n    \\\"type\\\": \\\"textarea\\\",\\n    \\\"content\\\": \\\"jkejkekfjkejfkjekfjekjfkjefj\\\",\\n    \\\"styles\\\": {\\n      \\\"height\\\": \\\"30px\\\"\\n    }\\n  },\\n  {\\n    \\\"type\\\": \\\"h2\\\",\\n    \\\"content\\\": \\\"ncjndcjcnec\\\",\\n    \\\"styles\\\": {\\n      \\\"class\\\": \\\"header-input h2\\\"\\n    }\\n  },\\n  {\\n    \\\"type\\\": \\\"image-container\\\",\\n    \\\"images\\\": [\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/uploads\\/677b0e66c0836_playlist2.jpeg\\\",\\n        \\\"width\\\": \\\"82px\\\",\\n        \\\"height\\\": \\\"82px\\\"\\n      },\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/assets\\/img\\/resize.png\\\",\\n        \\\"width\\\": \\\"auto\\\",\\n        \\\"height\\\": \\\"auto\\\"\\n      },\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/uploads\\/677b0e66c099d_pxfuel.jpg\\\",\\n        \\\"width\\\": \\\"auto\\\",\\n        \\\"height\\\": \\\"auto\\\"\\n      },\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/assets\\/img\\/resize.png\\\",\\n        \\\"width\\\": \\\"auto\\\",\\n        \\\"height\\\": \\\"auto\\\"\\n      }\\n    ]\\n  },\\n  {\\n    \\\"type\\\": \\\"image-container\\\",\\n    \\\"images\\\": [\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/uploads\\/677b0ead52ead_5818602-3840x2160-desktop-hd-laptop-wallpaper-photo.jpg\\\",\\n        \\\"width\\\": \\\"auto\\\",\\n        \\\"height\\\": \\\"auto\\\"\\n      },\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/assets\\/img\\/resize.png\\\",\\n        \\\"width\\\": \\\"auto\\\",\\n        \\\"height\\\": \\\"auto\\\"\\n      }\\n    ]\\n  },\\n  {\\n    \\\"type\\\": \\\"h2\\\",\\n    \\\"content\\\": \\\"ncjdnknwknecev\\\",\\n    \\\"styles\\\": {\\n      \\\"class\\\": \\\"header-input h2\\\"\\n    }\\n  }\\n]\"', '2025-01-05 23:58:57'),
(9, 'Znaczniki', 'HTML', '\"[\\n  {\\n    \\\"type\\\": \\\"h3\\\",\\n    \\\"content\\\": \\\"Aha\\\",\\n    \\\"styles\\\": {\\n      \\\"class\\\": \\\"header-input h3\\\"\\n    }\\n  },\\n  {\\n    \\\"type\\\": \\\"textarea\\\",\\n    \\\"content\\\": \\\"qwerty\\\",\\n    \\\"styles\\\": {\\n      \\\"height\\\": \\\"30px\\\"\\n    }\\n  },\\n  {\\n    \\\"type\\\": \\\"image-container\\\",\\n    \\\"images\\\": [\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/uploads\\/677b0eddb7374_5818602-3840x2160-desktop-hd-laptop-wallpaper-photo.jpg\\\",\\n        \\\"width\\\": \\\"auto\\\",\\n        \\\"height\\\": \\\"auto\\\"\\n      },\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/assets\\/img\\/resize.png\\\",\\n        \\\"width\\\": \\\"auto\\\",\\n        \\\"height\\\": \\\"auto\\\"\\n      }\\n    ]\\n  },\\n  {\\n    \\\"type\\\": \\\"h3\\\",\\n    \\\"content\\\": \\\"siemanko\\\",\\n    \\\"styles\\\": {\\n      \\\"class\\\": \\\"header-input h3\\\"\\n    }\\n  },\\n  {\\n    \\\"type\\\": \\\"image-container\\\",\\n    \\\"images\\\": [\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/uploads\\/677b0f1e17581_google.png\\\",\\n        \\\"width\\\": \\\"184px\\\",\\n        \\\"height\\\": \\\"184px\\\"\\n      },\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/assets\\/img\\/resize.png\\\",\\n        \\\"width\\\": \\\"auto\\\",\\n        \\\"height\\\": \\\"auto\\\"\\n      }\\n    ]\\n  },\\n  {\\n    \\\"type\\\": \\\"h3\\\",\\n    \\\"content\\\": \\\"Jak tam\\\",\\n    \\\"styles\\\": {\\n      \\\"class\\\": \\\"header-input h3\\\"\\n    }\\n  }\\n]\"', '2025-01-06 00:01:07'),
(10, 'Selektory', 'CSS', '\"[\\n  {\\n    \\\"type\\\": \\\"h2\\\",\\n    \\\"content\\\": \\\"Lekcja o selektorach w css\\\",\\n    \\\"styles\\\": {\\n      \\\"class\\\": \\\"header-input h2\\\"\\n    }\\n  },\\n  {\\n    \\\"type\\\": \\\"image-container\\\",\\n    \\\"images\\\": [\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/uploads\\/677b2dd68c4b0_images.jpeg\\\",\\n        \\\"width\\\": \\\"218px\\\",\\n        \\\"height\\\": \\\"218px\\\"\\n      },\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/assets\\/img\\/resize.png\\\",\\n        \\\"width\\\": \\\"auto\\\",\\n        \\\"height\\\": \\\"auto\\\"\\n      }\\n    ]\\n  },\\n  {\\n    \\\"type\\\": \\\"textarea\\\",\\n    \\\"content\\\": \\\"Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry\'s standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.\\\",\\n    \\\"styles\\\": {\\n      \\\"height\\\": \\\"525px\\\"\\n    }\\n  },\\n  {\\n    \\\"type\\\": \\\"h2\\\",\\n    \\\"content\\\": \\\"Why do we use it?\\\",\\n    \\\"styles\\\": {\\n      \\\"class\\\": \\\"header-input h2\\\"\\n    }\\n  },\\n  {\\n    \\\"type\\\": \\\"textarea\\\",\\n    \\\"content\\\": \\\"There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable. If you are going to use a passage of Lorem Ipsum, you need to be sure there isn\'t anything embarrassing hidden in the middle of text.\\\",\\n    \\\"styles\\\": {\\n      \\\"height\\\": \\\"300px\\\"\\n    }\\n  },\\n  {\\n    \\\"type\\\": \\\"image-container\\\",\\n    \\\"images\\\": [\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/uploads\\/677b2e1a89452_5818602-3840x2160-desktop-hd-laptop-wallpaper-photo.jpg\\\",\\n        \\\"width\\\": \\\"auto\\\",\\n        \\\"height\\\": \\\"auto\\\"\\n      },\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/assets\\/img\\/resize.png\\\",\\n        \\\"width\\\": \\\"auto\\\",\\n        \\\"height\\\": \\\"auto\\\"\\n      }\\n    ]\\n  },\\n  {\\n    \\\"type\\\": \\\"h2\\\",\\n    \\\"content\\\": \\\"Sed ut perspiciatis\\\",\\n    \\\"styles\\\": {\\n      \\\"class\\\": \\\"header-input h2\\\"\\n    }\\n  },\\n  {\\n    \\\"type\\\": \\\"textarea\\\",\\n    \\\"content\\\": \\\"Cicero famously orated against his political opponent Lucius Sergius Catilina. Occasionally the first Oration against Catiline is taken for type specimens: Quo usque tandem abutere, Catilina, patientia nostra? Quam diu etiam furor iste tuus nos eludet? (How long, O Catiline, will you abuse our patience? And for how long will that madness of yours mock us?)\\\",\\n    \\\"styles\\\": {\\n      \\\"height\\\": \\\"315px\\\"\\n    }\\n  },\\n  {\\n    \\\"type\\\": \\\"h3\\\",\\n    \\\"content\\\": \\\"In a professional context...\\\",\\n    \\\"styles\\\": {\\n      \\\"class\\\": \\\"header-input h3\\\"\\n    }\\n  },\\n  {\\n    \\\"type\\\": \\\"textarea\\\",\\n    \\\"content\\\": \\\"Lorem Ipsum is a tool that can be useful, used intentionally it may help solve some problems. If you go about content strategy the wrong way, fix that problem.\\\",\\n    \\\"styles\\\": {\\n      \\\"height\\\": \\\"150px\\\"\\n    }\\n  },\\n  {\\n    \\\"type\\\": \\\"image-container\\\",\\n    \\\"images\\\": [\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/uploads\\/677b2eb6869cc_playlist2.jpeg\\\",\\n        \\\"width\\\": \\\"126px\\\",\\n        \\\"height\\\": \\\"126px\\\"\\n      },\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/assets\\/img\\/resize.png\\\",\\n        \\\"width\\\": \\\"auto\\\",\\n        \\\"height\\\": \\\"auto\\\"\\n      },\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/uploads\\/677b2eb686b33_pxfuel.jpg\\\",\\n        \\\"width\\\": \\\"auto\\\",\\n        \\\"height\\\": \\\"auto\\\"\\n      },\\n      {\\n        \\\"src\\\": \\\"http:\\/\\/localhost\\/w3schools\\/assets\\/img\\/resize.png\\\",\\n        \\\"width\\\": \\\"auto\\\",\\n        \\\"height\\\": \\\"auto\\\"\\n      }\\n    ]\\n  }\\n]\"', '2025-01-06 02:16:18');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` text NOT NULL,
  `image` longblob NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `kursy`
--
ALTER TABLE `kursy`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contact`
--
ALTER TABLE `contact`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `kursy`
--
ALTER TABLE `kursy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
