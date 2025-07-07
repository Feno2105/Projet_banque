  INSERT INTO client (nom_client, email, telephone, adresse)
  VALUES 
    ('Jean Dupont', 'jean.dupont@example.com', '+33601020304', '10 Rue de Paris, 75001 Paris'),
    ('Marie Curie', 'marie.curie@example.com', '+33611223344', '15 Avenue Victor Hugo, 75016 Paris'),
    ('Ali Ben Youssef', 'ali.youssef@example.com', '+212612345678', 'Quartier Agdal, Rabat, Maroc'),
    ('Lina Rasoanaivo', 'lina.rasoanaivo@example.mg', '+261320000001', 'Lot II F 45, Antananarivo, Madagascar'),
    ('Carlos Mendoza', 'carlos.mendoza@example.es', '+34612345678', 'Calle Mayor 23, Madrid, Espagne');


INSERT INTO type_pret (nom_type_pret, taux_interet, duree_mois, montant_min, montant_max)
VALUES
  ('Prêt Personnel', 5.50, 24, 1000.00, 20000.00),
  ('Prêt Immobilier', 3.20, 240, 50000.00, 500000.00),
  ('Crédit Auto', 4.80, 60, 3000.00, 50000.00),
  ('Microcrédit', 7.00, 12, 100.00, 1500.00);


INSERT INTO statut_pret (libelle) VALUES
  ('En cours'),   -- id = 1
  ('Terminé'),    -- id = 2
  ('En retard');  -- id = 3


INSERT INTO pret (client_id, type_pret_id, montant, reste_a_payer, date_debut, statut)
VALUES
  (1, 1, 5000.00, 3200.00, '2025-04-01', 1),
  (2, 2, 150000.00, 140000.00, '2023-06-15', 1),
  (3, 3, 20000.00, 0.00, '2022-09-10', 2),
  (4, 4, 800.00, 800.00, '2025-06-01', 1),
  (5, 1, 10000.00, 5000.00, '2024-01-20', 3);

INSERT INTO client (nom_client, email, telephone, adresse, date_inscription)
VALUES ('Jean Dupont', 'jean.dupont@email.com', '0612345678', '12 rue de Paris, 75001 Paris', '2025-11-01');