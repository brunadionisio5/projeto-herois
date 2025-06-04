CREATE DATABASE herois;
USE herois;

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usuario VARCHAR(50) NOT NULL,
    senha_hash VARCHAR(255) NOT NULL
);

INSERT INTO usuarios (usuario, senha_hash) VALUES ('admin', SHA2('1234', 256));

CREATE TABLE universo (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    logo VARCHAR(255) NOT NULL
);

CREATE TABLE heroi (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    imagem VARCHAR(255) NOT NULL,
    descricao TEXT NOT NULL,
    raridade ENUM('comum', 'incomum', 'raro', 'epico', 'lendario') NOT NULL,
    tipo ENUM('heroi', 'antiheroi', 'vilao') NOT NULL,
    universo_id INT,
    forca TINYINT UNSIGNED NOT NULL CHECK (forca BETWEEN 0 AND 100),
    velocidade TINYINT UNSIGNED NOT NULL CHECK (velocidade BETWEEN 0 AND 100),
    inteligencia TINYINT UNSIGNED NOT NULL CHECK (inteligencia BETWEEN 0 AND 100),
    vitalidade TINYINT UNSIGNED NOT NULL CHECK (vitalidade BETWEEN 0 AND 100),
    resistencia TINYINT UNSIGNED NOT NULL CHECK (resistencia BETWEEN 0 AND 100),
    FOREIGN KEY (universo_id) REFERENCES universo(id) ON DELETE SET NULL
);

CREATE TABLE habilidades (
    id SERIAL PRIMARY KEY,
    nome VARCHAR(100) NOT NULL,
    tipo VARCHAR(50),
    descricao TEXT
);

CREATE TABLE heroi_habilidade (
    id INT AUTO_INCREMENT PRIMARY KEY,
    heroi_id INT NOT NULL,
    habilidade_id INT NOT NULL,
    nivel TINYINT NOT NULL CHECK (nivel BETWEEN 1 AND 5),
    FOREIGN KEY (heroi_id) REFERENCES heroi(id) ON DELETE CASCADE,
    FOREIGN KEY (habilidade_id) REFERENCES habilidades(id) ON DELETE CASCADE
);



USE herois;

-- Habilidades Defensivas
INSERT INTO habilidades (nome, tipo, descricao) VALUES
('Campo de Força', 'Defensiva', 'Cria uma barreira protetora ao redor do herói ou aliados.'),
('Regeneração Rápida', 'Defensiva', 'Recupera vida automaticamente ao longo do tempo.'),
('Pele de Aço', 'Defensiva', 'Reduz o dano físico recebido drasticamente.'),
('Reflexo de Dano', 'Defensiva', 'Parte do dano recebido é devolvido ao atacante.'),
('Absorção de Energia', 'Defensiva', 'Converte ataques recebidos em energia para o herói.');

-- Habilidades Ofensivas
INSERT INTO habilidades (nome, tipo, descricao) VALUES
('Ataque de Energia', 'Ofensiva', 'Dispara um feixe de energia concentrada.'),
('Explosão Cinética', 'Ofensiva', 'Causa dano em área com uma onda de impacto.'),
('Golpe Fantasma', 'Ofensiva', 'Atinge o inimigo ignorando armaduras e escudos.'),
('Chamas Eternas', 'Ofensiva', 'Incendeia o alvo com fogo contínuo.'),
('Lâmina de Sombras', 'Ofensiva', 'Um ataque furtivo que causa dano crítico.');

-- Habilidades Psíquicas / Mentais
INSERT INTO habilidades (nome, tipo, descricao) VALUES
('Telepatia', 'Mental', 'Comunica-se mentalmente ou lê pensamentos.'),
('Controle Mental', 'Mental', 'Temporariamente controla um inimigo.'),
('Ilusão', 'Mental', 'Cria imagens falsas para enganar adversários.'),
('Previsão', 'Mental', 'Antecipação de movimentos inimigos.'),
('Paralisia Psíquica', 'Mental', 'Imobiliza o oponente usando a mente.');

-- Habilidades Elementais
INSERT INTO habilidades (nome, tipo, descricao) VALUES
('Manipulação de Fogo', 'Elemental', 'Controla e projeta chamas para ataque ou defesa.'),
('Controle da Água', 'Elemental', 'Manipula água em estado líquido para ataque, cura ou escudo.'),
('Comando sobre o Vento', 'Elemental', 'Cria rajadas cortantes ou voa com auxílio do vento.'),
('Domínio da Terra', 'Elemental', 'Move e molda rochas e solo como arma ou proteção.'),
('Gelo Congelante', 'Elemental', 'Congela alvos ou superfícies, podendo imobilizar inimigos.'),
('Raio / Eletricidade', 'Elemental', 'Dispara raios elétricos ou sobrecarrega dispositivos.');

-- Habilidades Espaciais / Temporais
INSERT INTO habilidades (nome, tipo, descricao) VALUES
('Teletransporte', 'Espacial/Temporal', 'Move-se instantaneamente para outro local visível.'),
('Parar o Tempo', 'Espacial/Temporal', 'Congela o tempo ao redor por alguns segundos.'),
('Distorção Espacial', 'Espacial/Temporal', 'Dobra o espaço para escapar ou criar armadilhas.'),
('Viagem Temporal', 'Espacial/Temporal', 'Permite retornar a eventos passados por breves momentos.'),
('Fenda Dimensional', 'Espacial/Temporal', 'Abre portais para outras realidades ou mundos.');

-- Habilidades Sensoriais
INSERT INTO habilidades (nome, tipo, descricao) VALUES
('Visão de Raios-X', 'Sensorial', 'Permite enxergar através de objetos sólidos.'),
('Audição Aguçada', 'Sensorial', 'Detecta sons imperceptíveis a ouvidos humanos.'),
('Sentido Aranha', 'Sensorial', 'Detecta perigos iminentes mesmo fora do campo de visão.'),
('Radar Biológico', 'Sensorial', 'Identifica presença de formas de vida próximas.'),
('Clairvoyance', 'Sensorial', 'Permite ver eventos em locais distantes em tempo real.');

-- Habilidades Físicas
INSERT INTO habilidades (nome, tipo, descricao) VALUES
('Força Sobre-Humana', 'Física', 'Capacidade de levantar e mover objetos extremamente pesados.'),
('Velocidade Relâmpago', 'Física', 'Corre a velocidades sobre-humanas, quase invisível a olho nu.'),
('Agilidade Felina', 'Física', 'Reflexos e equilíbrio excepcionais.'),
('Resistência Sobrenatural', 'Física', 'Suporta danos e fadiga muito além do normal.'),
('Salto Colossal', 'Física', 'Capaz de pular grandes distâncias e alturas.');

-- Habilidades Místicas / Mágicas
INSERT INTO habilidades (nome, tipo, descricao) VALUES
('Invocação de Criaturas', 'Mística/Mágica', 'Chama aliados mágicos temporários para ajudar em combate.'),
('Encantamentos', 'Mística/Mágica', 'Fortalece armas, armaduras ou aliados com efeitos mágicos.'),
('Controle de Espíritos', 'Mística/Mágica', 'Comunica-se e manipula entidades espirituais.'),
('Magia Negra', 'Mística/Mágica', 'Conjura feitiços destrutivos ou amaldiçoados.'),
('Magia Branca', 'Mística/Mágica', 'Conjura magias de cura e proteção.'),
('Transmutação', 'Mística/Mágica', 'Altera a matéria de um objeto ou ser.');

-- Habilidades Tecnológicas / Cibernéticas
INSERT INTO habilidades (nome, tipo, descricao) VALUES
('Hackeamento Mental', 'Tecnológica', 'Interfere com a mente de ciborgues ou seres conectados.'),
('Armas Integradas', 'Tecnológica', 'Possui armas embutidas no corpo ou traje.'),
('Interface com Máquinas', 'Tecnológica', 'Controla sistemas digitais com o pensamento.'),
('Drone de Combate', 'Tecnológica', 'Usa drones autônomos para ataque ou vigilância.'),
('Camuflagem Óptica', 'Tecnológica', 'Fica invisível por tempo limitado através de tecnologia avançada.');