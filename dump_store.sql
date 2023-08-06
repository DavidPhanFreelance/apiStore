-- Supprimer la table si elle existe déjà
DROP TABLE IF EXISTS magasin;

-- Créer la table
CREATE TABLE magasin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(255) NOT NULL
);

-- Insérer des noms aléatoires de magasins
INSERT INTO magasin (nom)
VALUES
    ('Magasin Apple'),
    ('Boutique Mode'),
    ('ElectroShop'),
    ('MegaMart'),
    ('SuperMarket'),
    ('Magasin Gourmet'),
    ('BricoShop'),
    ('Librairie LireBien'),
    ('Café Central'),
    ('Boutique de Jouets');
