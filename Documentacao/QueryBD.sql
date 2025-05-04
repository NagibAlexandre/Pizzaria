CREATE TABLE pizzas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao LONGTEXT NOT NULL,
    abreviacao VARCHAR(255) NOT NULL
);

CREATE TABLE usuarios (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(100),
  email VARCHAR(100) UNIQUE,
  senha VARCHAR(255),
  telefone VARCHAR(20),
  endereco TEXT
);

CREATE TABLE recibos (
    id INT PRIMARY KEY AUTO_INCREMENT,
    data DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    produtos_comprados TEXT NOT NULL,
    id_usuario INT NOT NULL,
    endereco_entrega VARCHAR(255) NOT NULL,
    valor DECIMAL(10, 2) NOT NULL,
    forma_pagamento ENUM('cartão', 'dinheiro') NOT NULL,
    FOREIGN KEY (id_usuario) REFERENCES usuarios(id)
);

INSERT INTO pizzas (nome, descricao, abreviacao) VALUES
('Quatro Queijos', 'Deliciosa pizza de quatro queijos especiais.', '4_queijo'),
('Americana Tradicional', 'Deliciosa pizza ao estilo americano.', 'america'),
('Bauru Clássico', 'Deliciosa pizza inspirada no tradicional sanduíche bauru.', 'bauru'),
('Calabresa Tradicional', 'Deliciosa pizza de calabresa levemente apimentada.', 'calabresa'),
('Calabresa Especial', 'Deliciosa pizza de calabresa com toque especial da casa.', 'calabresa_especial'),
('Carne Seca com Cream Cheese', 'Deliciosa pizza de carne seca com cremoso cream cheese.', 'carne_seca_cream_cheese'),
('Catuperoni', 'Deliciosa pizza de pepperoni com catupiry.', 'catuperoni'),
('Cheddar Cremoso', 'Deliciosa pizza com generosa camada de cheddar.', 'cheddar'),
('Cheddar com Bacon', 'Deliciosa pizza de cheddar e crocante bacon.', 'cheddar_bacon'),
('Cheddar com Pepperoni', 'Deliciosa pizza de cheddar com fatias de pepperoni.', 'cheddar_peperoni'),
('Ovo com Bacon', 'Deliciosa pizza com ovo e bacon crocante.', 'egg_bacon'),
('Extravagante Suprema', 'Deliciosa pizza com uma seleção generosa de ingredientes.', 'extravagante'),
('Frango com Cream Cheese', 'Deliciosa pizza de frango com cream cheese cremoso.', 'frango_cream_cheese'),
('Frango Grelhado', 'Deliciosa pizza com tiras de frango grelhado.', 'frango_grelhado'),
('Frango com Requeijão', 'Deliciosa pizza de frango com requeijão cremoso.', 'frango_requeijao'),
('La Bianca', 'Deliciosa pizza branca com sabores suaves e refinados.', 'labianca'),
('Margherita Tradicional', 'Deliciosa pizza margherita com manjericão fresco.', 'margherita'),
('Meat Bacon', 'Deliciosa pizza com carne e bacon.', 'meat_bacon'),
('Napolitana Clássica', 'Deliciosa pizza napolitana com sabor tradicional.', 'napolitana'),
('Pão de Alho', 'Deliciosa pizza com cobertura de pão de alho.', 'pao_de_alho'),
('Pepperoni Cup', 'Deliciosa pizza com pepperonis em formato de copinho.', 'peperoni_cup'),
('Festival de Pepperoni', 'Deliciosa pizza com extra de pepperoni.', 'peperoni_fest'),
('Pepe Rock', 'Deliciosa pizza com sabor ousado da linha Pepe Rock.', 'pepe_rock'),
('Pepperoni Clássico', 'Deliciosa pizza com fatias de pepperoni crocante.', 'pepperoni'),
('Portuguesa Completa', 'Deliciosa pizza portuguesa com ovos, presunto e ervilhas.', 'portuguesa'),
('Presunto Especial', 'Deliciosa pizza de presunto com queijo.', 'presunto'),
('Queijo Supremo', 'Deliciosa pizza com queijo derretido e saboroso.', 'queijo'),
('Vegetariana Especial', 'Deliciosa pizza com uma seleção de vegetais frescos.', 'veggie');
