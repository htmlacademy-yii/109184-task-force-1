CREATE TABLE `users` (
  `id` int PRIMARY KEY AUTO_INCREMENT,
  `role_id` int,
  `login` varchar(255),
  `password` varchar(255),
  `email` varchar(255),
  `name` varchar(255),
  `birthdate` int,
  `city_id` int,
  `phone` varchar(255),
  `skype` varchar(255),
  `telegram` varchar(255),
  `avatar` varchar(255),
  `specifications` text,
  `portfolio` text,
  `balance` decimal,
  `rating` decimal,
  `show_profile` tinyint,
  `show_contacts` tinyint,
  `created_at` int,
  `updated_at` int
);

CREATE TABLE `user_roles` (
  `id` int PRIMARY KEY,
  `role` varchar(255)
);

CREATE TABLE `cities` (
  `id` int PRIMARY KEY,
  `name` varchar(255)
);

CREATE TABLE `task_statuses` (
  `id` int PRIMARY KEY,
  `description` varchar(255)
);

CREATE TABLE `actions_list` (
  `id` int PRIMARY KEY,
  `description` varchar(255)
);

CREATE TABLE `task_actions` (
  `id` int PRIMARY KEY,
  `task_id` int,
  `action_id` int
);

CREATE TABLE `categories` (
  `id` int PRIMARY KEY,
  `name` varchar(255)
);

CREATE TABLE `work_types` (
  `id` int PRIMARY KEY,
  `name` varchar(255)
);

CREATE TABLE `responds` (
  `id` int PRIMARY KEY,
  `task_id` int,
  `user_id` int,
  `price` decimal,
  `comment` text,
  `created_at` int,
  `is_accepted` tinyint
);

CREATE TABLE `tasks` (
  `id` int PRIMARY KEY,
  `title` varchar(255),
  `description` text,
  `category_id` int,
  `work_type_id` int,
  `city_id` int,
  `price` decimal,
  `expire_date` int,
  `user_created` int,
  `status` int,
  `created_at` int
);

CREATE TABLE `messages` (
  `id` int PRIMARY KEY,
  `task_id` int,
  `user_from` int,
  `user_to` int,
  `text` text,
  `status` int,
  `created_at` int
);

CREATE TABLE `reviews` (
  `id` int PRIMARY KEY,
  `user_created` int,
  `text` text,
  `user_reciever` int,
  `rate` decimal,
  `created_at` int
);

CREATE TABLE `gallery` (
  `id` int PRIMARY KEY,
  `post_id` int,
  `post_type` varchar(255),
  `link` text,
  `user_id` int,
  `created_at` int
);

CREATE TABLE `favourites` (
  `id` int PRIMARY KEY,
  `user_added` int,
  `user_favourite` int,
  `created_at` int
);

ALTER TABLE `users` ADD FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`);

ALTER TABLE `users` ADD FOREIGN KEY (`role_id`) REFERENCES `user_roles` (`id`);

ALTER TABLE `tasks` ADD FOREIGN KEY (`user_created`) REFERENCES `users` (`id`);

ALTER TABLE `responds` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `messages` ADD FOREIGN KEY (`user_from`) REFERENCES `users` (`id`);

ALTER TABLE `messages` ADD FOREIGN KEY (`user_to`) REFERENCES `users` (`id`);

ALTER TABLE `reviews` ADD FOREIGN KEY (`user_created`) REFERENCES `users` (`id`);

ALTER TABLE `reviews` ADD FOREIGN KEY (`user_reciever`) REFERENCES `users` (`id`);

ALTER TABLE `gallery` ADD FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

ALTER TABLE `favourites` ADD FOREIGN KEY (`user_added`) REFERENCES `users` (`id`);

ALTER TABLE `favourites` ADD FOREIGN KEY (`user_favourite`) REFERENCES `users` (`id`);

ALTER TABLE `responds` ADD FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`);

ALTER TABLE `messages` ADD FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`);

ALTER TABLE `gallery` ADD FOREIGN KEY (`post_id`) REFERENCES `tasks` (`id`);

ALTER TABLE `tasks` ADD FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

ALTER TABLE `tasks` ADD FOREIGN KEY (`work_type_id`) REFERENCES `work_types` (`id`);

ALTER TABLE `tasks` ADD FOREIGN KEY (`city_id`) REFERENCES `cities` (`id`);

ALTER TABLE `tasks` ADD FOREIGN KEY (`status`) REFERENCES `task_statuses` (`id`);

ALTER TABLE `task_actions` ADD FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`);

ALTER TABLE `task_actions` ADD FOREIGN KEY (`action_id`) REFERENCES `actions_list` (`id`);
