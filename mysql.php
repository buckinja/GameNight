CREATE TABLE IF NOT EXISTS player (
	id INT AUTO_INCREMENT NOT NULL,
	fname VARCHAR(100) NOT NULL,
	lname VARCHAR(100) NOT NULL,
	uname VARCHAR(255) NOT NULL,
	pc BLOB NOT NULL,
	slt VARCHAR(255), 
	wins INT NOT NULL DEFAULT '0',
	PRIMARY KEY (id)
) TYPE=innodb;

CREATE TABLE IF NOT EXISTS game (
	id INT AUTO_INCREMENT NOT NULL,
	name VARCHAR(100) NOT NULL,
	genre VARCHAR(100),
	theme VARCHAR(100),
	times_played INT NOT NULL DEFAULT '0',
	PRIMARY KEY (id)
) TYPE=innodb;

CREATE TABLE IF NOT EXISTS game_rating (
	id INT AUTO_INCREMENT NOT NULL,
	gid INT NOT NULL,
	pid INT NOT NULL,
	rating DOUBLE,
	PRIMARY KEY (id),
	FOREIGN KEY (gid) REFERENCES game (id),
	FOREIGN KEY (pid) REFERENCES player (id)
) TYPE=innodb;

CREATE TABLE IF NOT EXISTS round (
	id INT AUTO_INCREMENT NOT NULL,
	date_played DATE,
	times_played INT NOT NULL DEFAULT '0',
	gid INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (gid) REFERENCES game (id)
) TYPE=innodb;

CREATE TABLE IF NOT EXISTS round_player (
	id INT AUTO_INCREMENT NOT NULL,
	win_status VARCHAR(100) NOT NULL DEFAULT 'lose',
	rid INT NOT NULL,
	pid INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY(rid) REFERENCES round(id),
	FOREIGN KEY(pid) REFERENCES player(id)
) TYPE=innodb;








Landing page
	sign up
		create record for player with fname and lname, username, pw
	sign in
		validate username, pw

Logged in
	menu
		sort by:
		all
			view group rating
				get average of ratings from game_rating where game_rating.gid = game.id
			your rating  
				get rating where game_rating.gid = game.id AND player.id = game_rating.pid
			genre
				get game.genre
			theme
				get game.theme
			button to view ranking
				get top 5 players where player.id = round_player.pid AND round_player.gid = game.id group by player.id 
					descending order on sum of rounds won

		genres
			view group rating
				get average of ratings from game_rating where game_rating.gid = game.id and game.genre = genre
			your rating  
				get rating where game_rating.gid = game.id AND player.id = game_rating.pid
			theme
				get game.theme
			button to view ranking
				get top 5 players where player.id = round_player.pid AND round_player.gid = game.id group by player.id 
					descending order on sum of rounds won

		ratings (group)
			get average of ratings from game_rating where game_rating.gid = game.id order descending by rating
				view group rating
					get average of ratings from game_rating where game_rating.gid = game.id order descending by rating
				your rating  
					get rating where game_rating.gid = game.id AND player.id = game_rating.pid
				genre
					get game.genre
				theme
					get game.theme
				button to view ranking
					get top 5 players where player.id = round_player.pid AND round_player.gid = game.id group by player.id 
						descending order on sum of rounds won
		ratings (you)
			get ratings from game_rating where game_rating.gid = game.id and game_rating.pid = you order descending by rating
				view group rating
					get average of ratings from game_rating where game_rating.gid = game.id order descending by rating
				your rating  
					get rating where game_rating.gid = game.id AND player.id = game_rating.pid
				genre
					get game.genre
				theme
					get game.theme
				button to view ranking
					get top 5 players where player.id = round_player.pid AND round_player.gid = game.id group by player.id 
						descending order on sum of rounds won
		theme
			view group rating
				get average of ratings from game_rating where game_rating.gid = game.id and game.theme = theme
			your rating  
				get rating where game_rating.gid = game.id AND player.id = game_rating.pid
			genre
				get game.genre
			button to view ranking
				get top 5 players where player.id = round_player.pid AND round_player.gid = game.id group by player.id 
					descending order on sum of rounds won
		popularity
			get times_played from game in descending order
				view group rating
					get average of ratings from game_rating where game_rating.gid = game.id and game.genre = genre
				your rating  
					get rating where game_rating.gid = game.id AND player.id = game_rating.pid
				theme
					get game.theme
				genre
				    get game.genre
				button to view ranking
					get top 5 players where player.id = round_player.pid AND round_player.gid = game.id group by player.id 
						descending order on sum of rounds won

	your group
		see stats
			most wins
				get lname, fname of first from wins from all players order descending
			most played game
				get game.name of first from times_played from all games order descending
			highest rated game
				get first game.name from ratings from game_rating where game_rating.gid = game.id descending by rating
			lowest rated game
			 	get first game.name from ratings from game_rating where game_rating.gid = game.id and rating != null, ascending by rating

	you
		favorite games
			get name from rating where game_rating.gid = game.id AND player.id = game_rating.pid AND rating > 3 and player.id = you order by rating descending
		number of total wins
			get wins from player = you
		game won most often
			get game.name of first sorted by count from (get count game.id from game=id = round.gid and round.id = round_player.rid and player.id = round_player.pid 
			and player.id = you) 


	game night generator
		input players' names
		input genre (optional)

		generate suggestions based on 1. collective rating
											get game.name from sum of rating where game_rating.gid = game.id AND player.id = game_rating.pid AND 
											rating > 3 and player.id = you or other players order by rating descending
									  2. most popular in selected genre
									  		get average of ratings from game_rating where game_rating.gid = game.id and game.genre = genre
									  		and game_rating.pid = player.id where player name = you or other players
									  3. random choice
									  		get and store highest game.id, get game.name of rand game.id between 0-highest

	enter round
		insert player(s)
			look up player ids (get player.id where player.uname = input)
		insert game
			look up game id (get game.id where game.name = input)
			create round record with game id
		insert winner(s) or tie/loss
			look up player ids and for each insert into round_player player, round reference, game id, and win status


