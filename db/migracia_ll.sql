SET NAMES 'utf8';
SET FOREIGN_KEY_CHECKS=0;

RENAME TABLE    attachments TO old_attachments,
                img TO old_img,
                missions TO old_missions,
                results TO old_results,
                solutions TO old_solutions,
                users TO old_users,
                videos TO old_videos;

CREATE TABLE users (
    user_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    mail VARCHAR(50) UNIQUE,
    password VARCHAR(50)
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

CREATE TABLE organisators (
    user_id INT UNSIGNED,
    `admin` BOOLEAN,
    validated BOOLEAN,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

CREATE TABLE teams (
    user_id INT UNSIGNED,
    name VARCHAR(30) UNIQUE,
    description TEXT,
    sk_league BOOLEAN,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

CREATE TABLE texts (
    text_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    sk TEXT,
    eng TEXT
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

CREATE TABLE contexts (
    context_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY NOT NULL,
    user_id INT UNSIGNED,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

CREATE TABLE assignments (
    context_id INT UNSIGNED UNIQUE NOT NULL,
    text_id_name INT UNSIGNED,
    text_id_description INT UNSIGNED,
    `begin` DATETIME,
    `end` DATETIME,
    `year` INTEGER,
    FOREIGN KEY (context_id) REFERENCES contexts(context_id) ON DELETE CASCADE,
    FOREIGN KEY (text_id_name) REFERENCES texts(text_id) ON DELETE CASCADE,
    FOREIGN KEY (text_id_description) REFERENCES texts(text_id) ON DELETE CASCADE
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

CREATE TABLE solutions (
    context_id INT UNSIGNED UNIQUE NOT NULL,
    assignment_id INT UNSIGNED,
    text TEXT,
    best BOOLEAN,
    FOREIGN KEY (context_id) REFERENCES contexts(context_id) ON DELETE CASCADE,
    FOREIGN KEY (assignment_id) REFERENCES assignments(context_id) ON DELETE SET NULL
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

CREATE TABLE comments (
    comment_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    solution_id INT UNSIGNED,
    user_id INT UNSIGNED,
    text TEXT,
    points FLOAT,
    FOREIGN KEY (solution_id) REFERENCES solutions(context_id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE SET NULL
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

CREATE TABLE videos (
    video_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    context_id INT UNSIGNED,
    link VARCHAR(11),
    FOREIGN KEY (context_id) REFERENCES contexts(context_id) ON DELETE SET NULL
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

CREATE TABLE programs (
    program_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    context_id INT UNSIGNED,
    original_name VARCHAR(100),
    FOREIGN KEY (context_id) REFERENCES contexts(context_id) ON DELETE SET NULL
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

CREATE TABLE images (
    image_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    context_id INT UNSIGNED,
    original_name VARCHAR(100),
    FOREIGN KEY (context_id) REFERENCES contexts(context_id) ON DELETE SET NULL
) CHARACTER SET utf8 COLLATE utf8_slovak_ci;

/*********************************************************************************/

DELETE FROM old_users WHERE id = 97;

SET @rownum = 0;

UPDATE old_users 
SET mail = CONCAT(mail, @rownum := @rownum + 1)
WHERE mail = 'L.etnaLigaFLL@gmail.com'
ORDER BY id ASC;

INSERT INTO users (mail, password)
SELECT u.mail, u.passwd
FROM old_users u
WHERE u.`type` != 0
ORDER BY u.`type` = 2 ASC, u.id ASC;

ALTER TABLE users AUTO_INCREMENT = 1;

SET @lastuserorg = (SELECT `AUTO_INCREMENT`-1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = (SELECT DATABASE() FROM DUAL) AND TABLE_NAME = 'users');

INSERT INTO organisators (user_id, `admin`, validated)
SELECT u.user_id, u.user_id = 1, TRUE
FROM users u
ORDER BY u.user_id;

INSERT INTO contexts (user_id)
SELECT 1
FROM old_missions;

ALTER TABLE contexts AUTO_INCREMENT = 1;

SET @lastmission = (SELECT `AUTO_INCREMENT`-1 FROM information_schema.TABLES WHERE TABLE_SCHEMA = (SELECT DATABASE() FROM DUAL) AND TABLE_NAME = 'contexts');

INSERT INTO texts (sk, eng)
SELECT name, null
FROM old_missions
ORDER BY id ASC;

ALTER TABLE texts AUTO_INCREMENT = 1;

INSERT INTO texts (sk, eng)
SELECT content, null
FROM old_missions
ORDER BY id ASC;

SET @rownum = 0;

INSERT INTO assignments (context_id, text_id_name, text_id_description, `begin`, `end`, `year`)
SELECT @rownum := @rownum+1 AS rownum, @rownum AS rownum2, @rownum + @lastmission AS desc_id, `start`, `end`, YEAR(`start`)
FROM old_missions
ORDER BY id ASC; 

INSERT INTO users (mail, password)
SELECT SUBSTRING_INDEX(GROUP_CONCAT(CAST(u.mail AS CHAR) ORDER BY s.id DESC), ',', 1 ) as mail, SUBSTRING_INDEX(GROUP_CONCAT(CAST(u.passwd AS CHAR) ORDER BY s.id DESC), ',', 1 ) as passwd
FROM old_users u
LEFT OUTER JOIN old_solutions s ON (s.`uid` = u.id)
WHERE u.`type` = 0
GROUP BY u.name
ORDER BY MIN(u.id) ASC;

ALTER TABLE users AUTO_INCREMENT = 1;

SET @rownum = @lastuserorg;

INSERT INTO teams (user_id, name, description, sk_league)
SELECT @rownum := @rownum + 1 AS rownum, u.name, null, true
FROM (  SELECT u.name, MIN(u.id) id
        FROM old_users u
        WHERE u.`type` = 0
        GROUP BY u.name) u
ORDER BY u.id ASC;

INSERT INTO contexts (user_id)
SELECT t.user_id
FROM old_solutions o_s
LEFT OUTER JOIN old_users o_u ON (o_u.id = o_s.uid)
LEFT OUTER JOIN teams t ON (t.name COLLATE 'utf8_general_ci' = o_u.name)
GROUP BY o_s.id
ORDER BY o_s.id ASC;

SET @rownum = @lastmission;

INSERT INTO solutions (context_id, assignment_id, text, best)
SELECT @rownum := @rownum +1 AS rownum, s.assignment_id, s.content, s.win
FROM (  SELECT a.context_id AS assignment_id, o_s.content, o_s.win, o_s.id
        FROM old_solutions o_s
        LEFT OUTER JOIN old_users o_u ON (o_u.id = o_s.id)
        LEFT OUTER JOIN teams t ON (t.name COLLATE 'utf8_general_ci' = o_u.name)
        LEFT OUTER JOIN old_missions o_m ON (o_m.id = o_s.`mid`)
        LEFT OUTER JOIN texts txt ON (txt.sk COLLATE 'utf8_general_ci' = o_m.name)
        LEFT OUTER JOIN assignments a ON (a.text_id_name = txt.text_id)
        GROUP BY o_s.id) s
ORDER BY s.id ASC;

INSERT INTO comments (solution_id, user_id, text, points)
SELECT s.context_id, 1, SUBSTRING_INDEX(GROUP_CONCAT(CAST(o_r.text AS CHAR)  ORDER BY o_r.id SEPARATOR '#$#$#$##'), '#$#$#$##', 1 ) as text, ROUND(AVG(o_r.points), 2) AS points
FROM old_results o_r
LEFT OUTER JOIN old_solutions o_s ON (o_s.id = o_r.sid)
LEFT OUTER JOIN solutions s ON (s.text COLLATE 'utf8_general_ci' = o_s.content)
GROUP BY s.context_id
ORDER BY s.context_id ASC;

INSERT INTO videos (context_id, link)
SELECT s.context_id, CASE (REPLACE(o_v.link, ' ', '') LIKE 'https%') WHEN TRUE THEN MID(o_v.link, 31, 11) ELSE MID(o_v.link, 30, 11) END AS link
FROM old_videos o_v
LEFT OUTER JOIN old_solutions o_s ON (o_s.id = o_v.sid)
LEFT OUTER JOIN solutions s ON (s.text COLLATE 'utf8_general_ci' = o_s.content)
ORDER BY o_v.id ASC;

/****************************************************************************************/
INSERT INTO images (context_id, original_name) 
SELECT a.context_id, x.file_name 
FROM texts txt 
INNER JOIN assignments a ON (a.text_id_name = txt.text_id) 
INNER JOIN (
    SELECT '2013.11_.18_.20_.18_.16_.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/2013.11_.18_.20_.18_.16_.png%' UNION
    SELECT '8590.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/8590.jpg%' UNION
    SELECT 'AplusB.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/AplusB.png%' UNION
    SELECT 'bloodhound.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/bloodhound.jpg%' UNION
    SELECT 'cern.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/cern.jpg%' UNION
    SELECT 'dierny_stitok.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/dierny_stitok.png%' UNION
    SELECT 'dvere.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/dvere.jpg%' UNION
    SELECT 'fll-rotacia.zip' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/fll-rotacia.zip%' UNION
    SELECT 'hochschornerovci.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/hochschornerovci.jpg%' UNION
    SELECT 'hojdacka.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/hojdacka.jpg%' UNION
    SELECT 'hroch.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/hroch.jpg%' UNION
    SELECT 'IMAG1418.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/IMAG1418.jpg%' UNION
    SELECT 'karty.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/karty.png%' UNION
    SELECT 'lol.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/lol.jpg%' UNION
    SELECT 'prevodovkas.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/prevodovkas.png%' UNION
    SELECT 'priepast.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/priepast.jpg%' UNION
    SELECT 'projektove_vyucovanie_fll_2014.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/projektove_vyucovanie_fll_2014.png%' UNION
    SELECT 'robocupathome.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/robocupathome.jpg%' UNION
    SELECT 'robotchallenge_hand.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/robotchallenge_hand.jpg%' UNION
    SELECT 'rotacia.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/rotacia.png%' UNION
    SELECT 'skibot.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/skibot.jpg%' UNION
    SELECT 'slalom_trat1.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/slalom_trat1.png%' UNION
    SELECT 'slalom_trat2.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/slalom_trat2.png%' UNION
    SELECT 'spock.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/spock.jpg%' UNION
    SELECT 'stastny_hroch.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/stastny_hroch.jpg%' UNION
    SELECT 'stopar.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/stopar.png%' UNION
    SELECT 'tilebot.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/tilebot.png%' UNION
    SELECT 'turing.gif' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/turing.gif%' UNION
    SELECT 'vehicles.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/vehicles.png%' UNION
    SELECT 'vehicles1.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/vehicles1.png%' UNION
    SELECT 'vehicles2.png' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/vehicles2.png%' UNION
    SELECT 'vlasske-orechy.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/vlasske-orechy.jpg%' UNION
    SELECT 'watson.jpg' as file_name, o_m.name FROM old_missions o_m WHERE o_m.content LIKE '%attachments/watson.jpg%') x 
ON (x.name = txt.sk COLLATE 'utf8_general_ci') 
ORDER BY x.file_name ASC, a.context_id ASC;

ALTER TABLE images AUTO_INCREMENT = 1;

INSERT INTO images (context_id, original_name) 
SELECT s.context_id, o_i.link
FROM old_img o_i
INNER JOIN old_solutions o_s ON (o_s.id = o_i.sid)
INNER JOIN solutions s ON (s.text COLLATE 'utf8_general_ci' = o_s.content)
ORDER BY o_i.link COLLATE 'utf8_slovak_ci' ASC ;

INSERT INTO programs (context_id, original_name) 
SELECT s.context_id, o_a.link
FROM old_attachments o_a
INNER JOIN old_solutions o_s ON (o_s.id = o_a.sid)
INNER JOIN solutions s ON (s.text COLLATE 'utf8_general_ci' = o_s.content)
ORDER BY o_a.link COLLATE 'utf8_slovak_ci' ASC;

ALTER TABLE users AUTO_INCREMENT = 1;
ALTER TABLE teams AUTO_INCREMENT = 1;
ALTER TABLE organisators AUTO_INCREMENT = 1;
ALTER TABLE contexts AUTO_INCREMENT = 1;
ALTER TABLE texts AUTO_INCREMENT = 1;
ALTER TABLE assignments AUTO_INCREMENT = 1;
ALTER TABLE solutions AUTO_INCREMENT = 1;
ALTER TABLE comments AUTO_INCREMENT = 1;
ALTER TABLE images AUTO_INCREMENT = 1;
ALTER TABLE programs AUTO_INCREMENT = 1;
ALTER TABLE videos AUTO_INCREMENT = 1;

UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/8590.jpg', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 16, 'images', 1), '.jpg')) WHERE txt.text_id = 46;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/AplusB.png', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 14, 'images', 2), '.png')) WHERE txt.text_id = 44;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/bloodhound.jpg', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 19, 'images', 3), '.jpg')) WHERE txt.text_id = 49;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/cern.jpg', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 24, 'images', 4), '.jpg')) WHERE txt.text_id = 54;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/dierny_stitok.png', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 30, 'images', 5), '.png')) WHERE txt.text_id = 60;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/dvere.jpg', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 17, 'images', 6), '.jpg')) WHERE txt.text_id = 47;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/hochschornerovci.jpg', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 27, 'images', 7), '.jpg')) WHERE txt.text_id = 57;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/hojdacka.jpg', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 21, 'images', 8), '.jpg')) WHERE txt.text_id = 51;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/hroch.jpg', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 12, 'images', 9), '.jpg')) WHERE txt.text_id = 42;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/karty.png', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 17, 'images', 10), '.png')) WHERE txt.text_id = 47;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/prevodovkas.png', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 29, 'images', 11), '.png')) WHERE txt.text_id = 59;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/priepast.jpg', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 20, 'images', 12), '.jpg')) WHERE txt.text_id = 50;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/projektove_vyucovanie_fll_2014.png', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 23, 'images', 13), '.png')) WHERE txt.text_id = 53;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/robocupathome.jpg', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 26, 'images', 14), '.jpg')) WHERE txt.text_id = 56;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/robotchallenge_hand.jpg', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 25, 'images', 15), '.jpg')) WHERE txt.text_id = 55;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/rotacia.png', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 18, 'images', 16), '.png')) WHERE txt.text_id = 48;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/skibot.jpg', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 11, 'images', 17), '.jpg')) WHERE txt.text_id = 41;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/slalom_trat1.png', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 27, 'images', 18), '.png')) WHERE txt.text_id = 57;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/slalom_trat2.png', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 27, 'images', 19), '.png')) WHERE txt.text_id = 57;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/spock.jpg', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 22, 'images', 20), '.jpg')) WHERE txt.text_id = 52;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/stastny_hroch.jpg', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 12, 'images', 21), '.jpg')) WHERE txt.text_id = 42;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/stopar.png', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 19, 'images', 22), '.png')) WHERE txt.text_id = 49;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/tilebot.png', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 13, 'images', 23), '.png')) WHERE txt.text_id = 43;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/turing.gif', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 21, 'images', 24), '.gif')) WHERE txt.text_id = 51;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/vehicles.png', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 16, 'images', 25), '.png')) WHERE txt.text_id = 46;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/vehicles2.png', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 16, 'images', 26), '.png')) WHERE txt.text_id = 46;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/vlasske-orechy.jpg', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 28, 'images', 27), '.jpg')) WHERE txt.text_id = 58;
UPDATE texts txt SET txt.sk = REPLACE(txt.sk, 'letnaliga/attachments/watson.jpg', CONCAT(CONCAT_WS('/', 'll', 'attachments', 'assignments', 21, 'images', 28), '.jpg')) WHERE txt.text_id = 51;

DROP TABLE old_attachments, old_img, old_missions, old_results, old_solutions, old_users, old_videos;
SET FOREIGN_KEY_CHECKS=1;
