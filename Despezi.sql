drop database if exists despezi;
CREATE DATABASE IF NOT EXISTS despezi;
USE despezi;

drop table if exists usuarios;

CREATE TABLE IF NOT EXISTS Usuarios (
id_usuario INT AUTO_INCREMENT PRIMARY KEY,
nome VARCHAR(100) NOT NULL,
email VARCHAR(100) NOT NULL UNIQUE,
senha VARCHAR(255) NOT NULL,
salario_mensal DECIMAL(10,2) NOT NULL
);

INSERT INTO Usuarios (nome, email, senha, salario_mensal)
VALUES ('Admin', 'admin@gmail.com', 'cefet123', 0);

drop table if exists categorias;

CREATE TABLE IF NOT EXISTS Categorias (
id_categoria INT AUTO_INCREMENT PRIMARY KEY,
nome_categoria VARCHAR(50) NOT NULL
);

drop table if exists despesas;

CREATE TABLE IF NOT EXISTS Despesas (
id_despesa INT AUTO_INCREMENT PRIMARY KEY,
valor DECIMAL(10,2) NOT NULL,
data DATE NOT NULL,
descricao VARCHAR(255),
id_usuario INT,
id_categoria INT,
FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
ON DELETE CASCADE ON UPDATE CASCADE,
FOREIGN KEY (id_categoria) REFERENCES Categorias(id_categoria)
ON DELETE SET NULL ON UPDATE CASCADE
);

drop table if exists Rendas;

CREATE TABLE IF NOT EXISTS Rendas (
id_renda INT AUTO_INCREMENT PRIMARY KEY,
valor_renda DECIMAL(10,2) NOT NULL,
data_renda DATE NOT NULL,
id_usuario INT,
saldo DECIMAL(10,2) NOT NULL,
FOREIGN KEY (id_usuario) REFERENCES Usuarios(id_usuario)
ON DELETE CASCADE ON UPDATE CASCADE
);

drop view if exists vw_informacoes_usuario_renda;

CREATE OR REPLACE VIEW vw_informacoes_usuario_renda AS
SELECT
u.id_usuario,
r.id_renda,
u.nome AS nome_usuario,
r.saldo,
r.valor_renda,
r.data_renda
FROM Usuarios u
LEFT JOIN Rendas r ON u.id_usuario = r.id_usuario;

CREATE OR REPLACE VIEW vw_saldo_mensal AS
SELECT
u.id_usuario,
u.nome,
u.salario_mensal,
IFNULL(SUM(d.valor), 0) AS total_despesas,
(u.salario_mensal - IFNULL(SUM(d.valor), 0)) AS saldo_mensal
FROM Usuarios u
LEFT JOIN Despesas d
ON u.id_usuario = d.id_usuario
AND MONTH(d.data) = MONTH(CURDATE())
AND YEAR(d.data) = YEAR(CURDATE())
GROUP BY u.id_usuario;
CREATE OR REPLACE VIEW vw_projecao_financeira AS
SELECT
u.id_usuario,
u.nome,
AVG(mensal.total_despesas) AS media_despesas,
(u.salario_mensal - AVG(mensal.total_despesas)) AS saldo_projetado
FROM Usuarios u
JOIN (
SELECT
id_usuario,
YEAR(data) AS ano,
MONTH(data) AS mes,
SUM(valor) AS total_despesas
FROM Despesas
GROUP BY id_usuario, ano, mes
) AS mensal
ON u.id_usuario = mensal.id_usuario
GROUP BY u.id_usuario;
CREATE OR REPLACE VIEW vw_percentual_categorias AS
SELECT
d.id_usuario,
c.nome_categoria,
SUM(d.valor) AS total_categoria,
(SUM(d.valor) / (SELECT SUM(valor)
FROM Despesas
WHERE id_usuario = d.id_usuario
AND MONTH(data) = MONTH(CURDATE())
AND YEAR(data) = YEAR(CURDATE()))) * 100 AS percentual
FROM Despesas d
JOIN Categorias c ON d.id_categoria = c.id_categoria
WHERE MONTH(d.data) = MONTH(CURDATE()) AND YEAR(d.data) = YEAR(CURDATE())
GROUP BY d.id_usuario, c.id_categoria;
DELIMITER //
CREATE PROCEDURE sp_registrar_despesa (
IN p_id_usuario INT,
IN p_id_categoria INT,
IN p_valor DECIMAL(10,2),
IN p_data DATE,
IN p_descricao VARCHAR(255)
)
BEGIN
INSERT INTO Despesas (id_usuario, id_categoria, valor, data, descricao)
VALUES (p_id_usuario, p_id_categoria, p_valor, p_data, p_descricao);
END //

CREATE PROCEDURE sp_resumo_mensal(IN p_id_usuario INT)
BEGIN
SELECT
u.nome,
u.salario_mensal,
IFNULL(SUM(d.valor), 0) AS total_despesas,
(u.salario_mensal - IFNULL(SUM(d.valor), 0)) AS saldo_mensal,
ROUND((IFNULL(SUM(d.valor), 0) / u.salario_mensal) * 100, 2) AS percentual_gasto
FROM Usuarios u
LEFT JOIN Despesas d
ON u.id_usuario = d.id_usuario
AND MONTH(d.data) = MONTH(CURDATE())
AND YEAR(d.data) = YEAR(CURDATE())
WHERE u.id_usuario = p_id_usuario
GROUP BY u.id_usuario;
END //
DELIMITER ;

-- Inserir usuários e categorias de exemplo
INSERT INTO Usuarios (nome, email, senha, salario_mensal)
VALUES ('Guilherme', 'gui@email.com', '1234', 2000.00);
INSERT INTO Categorias (nome_categoria)
VALUES ('Alimentação'), ('Transporte'), ('Lazer');
-- Inserir algumas despesas
CALL sp_registrar_despesa(1, 1, 25.00, '2025-11-01', 'Café da manhã');
CALL sp_registrar_despesa(1, 1, 80.00, '2025-11-02', 'Almoço');
CALL sp_registrar_despesa(1, 2, 20.00, '2025-11-03', 'Ônibus');
CALL sp_registrar_despesa(1, 3, 50.00, '2025-11-04', 'Cinema');
SELECT * FROM vw_saldo_mensal WHERE id_usuario = 1;
SELECT * FROM vw_projecao_financeira WHERE id_usuario = 1;
SELECT * FROM vw_percentual_categorias WHERE id_usuario = 1;
CALL sp_resumo_mensal(1);
CREATE OR REPLACE VIEW vw_informacoes_usuario AS
SELECT
u.id_usuario,
u.nome AS nome_usuario,
d.descricao,
d.valor,
d.data,
c.nome_categoria
FROM Usuarios u
LEFT JOIN Despesas d ON u.id_usuario = d.id_usuario
LEFT JOIN Categorias c ON d.id_categoria = c.id_categoria;

select * from usuarios;
select * from despesas;
select * from categorias;