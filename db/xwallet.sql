-- phpMyAdmin SQL Dump
-- version 5.0.4
-- https://www.phpmyadmin.net/
--
-- Хост: 127.0.0.1:3306
-- Время создания: Июл 12 2021 г., 10:37
-- Версия сервера: 8.0.19
-- Версия PHP: 8.0.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База данных: `xwallet`
--

-- --------------------------------------------------------

--
-- Структура таблицы `accounts`
--

CREATE TABLE `accounts` (
  `acc_id` int NOT NULL,
  `type` varchar(15) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `accounts`
--

INSERT INTO `accounts` (`acc_id`, `type`) VALUES
(1, 'single'),
(2, 'family');

-- --------------------------------------------------------

--
-- Структура таблицы `spending`
--

CREATE TABLE `spending` (
  `s_id` int NOT NULL,
  `type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `spending`
--

INSERT INTO `spending` (`s_id`, `type`) VALUES
(1, 'credit'),
(2, 'food'),
(3, 'alcohol'),
(4, 'apartment rent'),
(5, 'household expenses'),
(6, 'clothes'),
(7, 'other'),
(8, 'not spent');

-- --------------------------------------------------------

--
-- Структура таблицы `users`
--

CREATE TABLE `users` (
  `u_id` int NOT NULL,
  `login` varchar(50) NOT NULL,
  `password` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `w_id` int NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `users`
--

INSERT INTO `users` (`u_id`, `login`, `password`, `email`, `w_id`) VALUES
(21, 'Polly', '$2y$10$uzAQw9SLHvJiKhfPKdo5COpZ3crwyfIPnYvd1jTp74fZunmVSavx6', 'serezhenka-gorshkov@mail.ru', 15),
(22, 'Papaya', '$2y$10$T2WSRdceIZktopnaiujkk.bYTm1YYN7BWzOfPlV6bMObI8zUiPFm6', 'papaya@mail.ru', 16);

-- --------------------------------------------------------

--
-- Структура таблицы `wallets`
--

CREATE TABLE `wallets` (
  `w_id` int NOT NULL,
  `acc_id` int NOT NULL,
  `accumulated` int DEFAULT NULL,
  `total_amount` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `wallets`
--

INSERT INTO `wallets` (`w_id`, `acc_id`, `accumulated`, `total_amount`) VALUES
(15, 1, 200, 650),
(16, 1, NULL, -200);

-- --------------------------------------------------------

--
-- Структура таблицы `wasted`
--

CREATE TABLE `wasted` (
  `waste_id` int NOT NULL,
  `w_id` int NOT NULL,
  `s_id` int NOT NULL,
  `amount` int NOT NULL,
  `date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Дамп данных таблицы `wasted`
--

INSERT INTO `wasted` (`waste_id`, `w_id`, `s_id`, `amount`, `date`) VALUES
(24, 15, 8, 200, '2021-07-12'),
(25, 16, 1, 50, '2021-07-12'),
(27, 15, 3, 150, '2021-06-12');

--
-- Индексы сохранённых таблиц
--

--
-- Индексы таблицы `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`acc_id`);

--
-- Индексы таблицы `spending`
--
ALTER TABLE `spending`
  ADD PRIMARY KEY (`s_id`);

--
-- Индексы таблицы `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`u_id`),
  ADD KEY `w_id` (`w_id`);

--
-- Индексы таблицы `wallets`
--
ALTER TABLE `wallets`
  ADD PRIMARY KEY (`w_id`),
  ADD KEY `acc_id` (`acc_id`);

--
-- Индексы таблицы `wasted`
--
ALTER TABLE `wasted`
  ADD PRIMARY KEY (`waste_id`),
  ADD KEY `s_id` (`s_id`),
  ADD KEY `w_id` (`w_id`);

--
-- AUTO_INCREMENT для сохранённых таблиц
--

--
-- AUTO_INCREMENT для таблицы `accounts`
--
ALTER TABLE `accounts`
  MODIFY `acc_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT для таблицы `spending`
--
ALTER TABLE `spending`
  MODIFY `s_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблицы `users`
--
ALTER TABLE `users`
  MODIFY `u_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT для таблицы `wallets`
--
ALTER TABLE `wallets`
  MODIFY `w_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT для таблицы `wasted`
--
ALTER TABLE `wasted`
  MODIFY `waste_id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- Ограничения внешнего ключа сохраненных таблиц
--

--
-- Ограничения внешнего ключа таблицы `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`w_id`) REFERENCES `wallets` (`w_id`);

--
-- Ограничения внешнего ключа таблицы `wallets`
--
ALTER TABLE `wallets`
  ADD CONSTRAINT `wallets_ibfk_1` FOREIGN KEY (`acc_id`) REFERENCES `accounts` (`acc_id`);

--
-- Ограничения внешнего ключа таблицы `wasted`
--
ALTER TABLE `wasted`
  ADD CONSTRAINT `wasted_ibfk_2` FOREIGN KEY (`s_id`) REFERENCES `spending` (`s_id`),
  ADD CONSTRAINT `wasted_ibfk_3` FOREIGN KEY (`w_id`) REFERENCES `wallets` (`w_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
