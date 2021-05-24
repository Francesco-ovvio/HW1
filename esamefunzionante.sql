-- phpMyAdmin SQL Dump
-- version 5.0.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Creato il: Mag 24, 2021 alle 20:58
-- Versione del server: 10.4.14-MariaDB
-- Versione PHP: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `esamefunzionante`
--

DELIMITER $$
--
-- Procedure
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `cambioProdotto` (IN `dip` VARCHAR(16), IN `qProd` INT, IN `codNuovoProd` INT)  begin
	start transaction;
		insert into STORICOPRODUZIONE(quantitaProdotta, orarioFine, dataFine, IDdip, IDprodotto) values (qProd, current_time, CURRENT_DATE, dip, (select prodottoAttuale from DIPENDENTE where CF=dip));
		
		update DIPENDENTE
		set prodottoAttuale=codNuovoProd, dataInizio=current_date, orarioInizio=current_time
		where CF=dip;
	commit;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `cercaCat` (IN `tip` VARCHAR(9))  BEGIN
	drop TEMPORARY TABLE if EXISTS temp;
    create TEMPORARY TABLE temp(
        IDprodotto int,
        cognome varchar(255),
        nome varchar(255),
        quantitaProdotta int DEFAULT null,
        dataFine date DEFAULT null);
    
    INSERT INTO temp
    SELECT SP.IDprodotto, D.cognome, D.nome, SP.quantitaProdotta, SP.dataFine
    from dipendente D, storicoproduzione SP
    where D.CF=SP.IDdip and tip=(select tipologia FROM tipoprodotto where IDprodotto=SP.IDprodotto);
    
    INSERT INTO temp (IDprodotto, cognome, nome)
    select D1.prodottoAttuale, D1.cognome, D1.nome
   	FROM dipendente D1
    WHERE tip=(SELECT tipologia from tipoprodotto where D1.prodottoAttuale=IDprodotto);
    
    select * from temp;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `dettagliCliente` (IN `idCli` INT)  BEGIN
	select COUNT(IDordine), pagato, sum(costoTot)
    from ordine
    where IDcliente=idCli
    GROUP by pagato;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `mansDipendente` (IN `mans` VARCHAR(16))  begin
	select nome, cognome
    from dipendente
    where mansione = mans;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `nuovoOrdine` (IN `cliente` VARCHAR(16), IN `settDep` INT, IN `qRichiesta` INT, IN `dipMag` VARCHAR(16))  BEGIN
	start TRANSACTION;
    	INSERT into ordine(nDeposito, IDcliente, IDmagazz, quantitaRichiesta, dataOrdine, costoTot, pagato) values
        	(settDep, cliente, dipMag, qRichiesta, CURRENT_DATE, (select costouni from prezzounitinv where settD=settDep)*qRichiesta, false);
        case
        	when (qRichiesta <= (select quantitaTot from inventario where settoreDeposito=settDep)) then
            	UPDATE inventario
                set quantitaTot = quantitaTot - qRichiesta, spazioDisp= spazioDisp+qRichiesta
                where settoreDeposito=settDep;
            else
            	SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'La richiesta supera la massima capacità di inventario';
        end case;
    commit;
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `nuovoProdotto` (IN `nomeP` VARCHAR(255), IN `tipo` VARCHAR(9), IN `cpu` FLOAT, IN `descr` VARCHAR(255), IN `img` VARCHAR(255), IN `nomeTrad` VARCHAR(255))  begin
	insert into TIPOPRODOTTO(nomeProdotto, tipologia, costoPerUnita, descrizione, img, nomeTrad) values (nomeP, tipo, cpu, descr, img, nomeTrad);
end$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `pagaOrdine` (IN `idord` INT)  BEGIN
	update ordine
    set pagato = true
    WHERE IDordine=idord;
end$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `cliente`
--

CREATE TABLE `cliente` (
  `pIva` varchar(16) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `cognome` varchar(255) NOT NULL,
  `indirizzo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `cliente`
--

INSERT INTO `cliente` (`pIva`, `nome`, `cognome`, `indirizzo`) VALUES
('13579', 'Giangiorgio', 'Bevilacqua', 'via Menestrello 3'),
('24680', 'Giancarlo', 'Fragalà', 'Via Rossi 5'),
('53806', 'Gigi', 'del Popolo', 'Via Mazzini 1'),
('6njfji06', 'Giuseppe', 'Peppino', 'Via da mo casa 1'),
('86427', 'Giuseppe', 'Garibaldi', 'Via Pacinotti 8'),
('97037', 'Enrico', 'Pratticò', 'Via San Giovanni 34'),
('abc123', 'Francesco', 'Vattiato', 'Via Pacinotti 135');

-- --------------------------------------------------------

--
-- Struttura della tabella `dipendente`
--

CREATE TABLE `dipendente` (
  `CF` varchar(16) NOT NULL,
  `nome` varchar(255) DEFAULT NULL,
  `cognome` varchar(255) DEFAULT NULL,
  `mansione` varchar(16) NOT NULL,
  `prodottoAttuale` int(11) DEFAULT NULL,
  `orarioInizio` time DEFAULT NULL,
  `dataInizio` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `dipendente`
--

INSERT INTO `dipendente` (`CF`, `nome`, `cognome`, `mansione`, `prodottoAttuale`, `orarioInizio`, `dataInizio`) VALUES
('abc123', 'Mario', 'Rossi', 'magazziniere', NULL, '17:07:56', '2021-05-01'),
('dqwth58', 'Enrico', 'Enrichino', 'magazziniere', NULL, '17:07:56', '2021-05-01'),
('efg456', 'Giuseppe', 'Vasta', 'addettoCereali', 4, '12:04:55', '2021-05-04'),
('fwke6', 'Franco', 'Franchino', 'addettoCereali', 7, '16:00:01', '2021-05-24'),
('hil789', 'Carlo', 'Carletto', 'addettoFormaggi', 3, '17:20:36', '2021-05-01'),
('jhoe08', 'Lucio', 'Lupo', 'addettoFormaggi', 2, '17:20:36', '2021-05-01');

--
-- Trigger `dipendente`
--
DELIMITER $$
CREATE TRIGGER `cambioProdottoDip` BEFORE UPDATE ON `dipendente` FOR EACH ROW begin
	if ((new.mansione='addettoFormaggi' and (select TPR1.tipologia from TIPOPRODOTTO TPR1 where TPR1.IDprodotto=new.prodottoAttuale)!='latticino') or
		(new.mansione='addettoCereali' and (select TPR2.tipologia from TIPOPRODOTTO TPR2 where TPR2.IDprodotto=new.prodottoAttuale)!='cereale')) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Il dipendente non è autorizato a produrre quel prodotto';
	end if;
	
	if (new.mansione='magazziniere') then
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Il magazziniere non può essere coinvolto in un processo di produzione';
	end if;
end
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `checkDipendente` BEFORE INSERT ON `dipendente` FOR EACH ROW begin
	if (new.mansione='magazziniere' and new.prodottoAttuale is not null) then
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Il magazziniere non può essere coinvolto in un processo di produzione';
	end if;
	
	if ((new.mansione='addettoFormaggi' and (select TPR1.tipologia from TIPOPRODOTTO TPR1 where TPR1.IDprodotto=new.prodottoAttuale)!='latticino') or
		(new.mansione='addettoCereali' and (select TPR2.tipologia from TIPOPRODOTTO TPR2 where TPR2.IDprodotto=new.prodottoAttuale)!='cereale')) then
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Verifica la mansione del dipendente e il tipo di prodotto';
	end if;
	
	if((new.mansione='addettoFormaggi' or new.mansione='addettoCereali') and new.prodottoAttuale is null) then
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Verifica che il dipendente abbia un prodotto da produrre assegnato';
	end if;
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `inventario`
--

CREATE TABLE `inventario` (
  `settoreDeposito` int(11) NOT NULL,
  `tipoProd` int(11) DEFAULT NULL,
  `quantitaTot` int(11) DEFAULT 0,
  `spazioDisp` int(11) DEFAULT 200
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `inventario`
--

INSERT INTO `inventario` (`settoreDeposito`, `tipoProd`, `quantitaTot`, `spazioDisp`) VALUES
(1, 1, 20, 180),
(2, 3, 18, 182),
(7, 7, 10, 190),
(8, 2, 3, 197),
(9, 6, 5, 195),
(10, 5, 7, 193),
(12, 4, 15, 185),
(13, 0, 0, 200);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `oldbuy`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `oldbuy` (
`IDordine` int(11)
,`nDeposito` int(11)
,`IDcliente` varchar(16)
,`IDmagazz` varchar(16)
,`quantitaRichiesta` int(11)
,`dataOrdine` date
,`costoTot` float
,`pagato` tinyint(1)
,`settoreDeposito` int(11)
,`tipoProd` int(11)
,`quantitaTot` int(11)
,`spazioDisp` int(11)
,`IDprodotto` int(11)
,`nomeProdotto` varchar(255)
,`tipologia` varchar(9)
,`costoPerUnita` float
,`descrizione` varchar(255)
,`img` varchar(255)
);

-- --------------------------------------------------------

--
-- Struttura della tabella `ordine`
--

CREATE TABLE `ordine` (
  `IDordine` int(11) NOT NULL,
  `nDeposito` int(11) DEFAULT NULL,
  `IDcliente` varchar(16) DEFAULT NULL,
  `IDmagazz` varchar(16) DEFAULT NULL,
  `quantitaRichiesta` int(11) DEFAULT NULL,
  `dataOrdine` date DEFAULT NULL,
  `costoTot` float DEFAULT NULL,
  `pagato` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `ordine`
--

INSERT INTO `ordine` (`IDordine`, `nDeposito`, `IDcliente`, `IDmagazz`, `quantitaRichiesta`, `dataOrdine`, `costoTot`, `pagato`) VALUES
(1, 1, '53806', 'abc123', 2, '2021-05-17', 5, 1),
(2, 8, '53806', 'dqwth58', 2, '2021-05-17', 6.6, 1),
(3, 2, '53806', 'dqwth58', 2, '2021-05-17', 6, 0),
(4, 1, '6njfji06', 'dqwth58', 6, '2021-05-20', 15, 1),
(5, 10, '6njfji06', 'abc123', 3, '2021-05-20', 11.4, 0);

-- --------------------------------------------------------

--
-- Struttura stand-in per le viste `prezzounitinv`
-- (Vedi sotto per la vista effettiva)
--
CREATE TABLE `prezzounitinv` (
`settD` int(11)
,`costouni` float
);

-- --------------------------------------------------------

--
-- Struttura della tabella `prodotto`
--

CREATE TABLE `prodotto` (
  `nTimbro` int(11) NOT NULL,
  `tipoPr` int(11) NOT NULL,
  `prProdutt` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `prodotto`
--

INSERT INTO `prodotto` (`nTimbro`, `tipoPr`, `prProdutt`) VALUES
(1029, 4, 20),
(1129, 3, 2),
(1211, 1, 12),
(1390, 3, 2),
(1643, 6, 9),
(1672, 1, 1),
(1818, 1, 12),
(2012, 1, 12),
(2099, 7, 7),
(2147, 4, 20),
(2273, 1, 1),
(2319, 3, 2),
(2393, 3, 2),
(2504, 3, 2),
(2534, 5, 11),
(2564, 5, 11),
(2583, 1, 1),
(2592, 1, 1),
(2694, 3, 2),
(2742, 2, 8),
(2783, 1, 1),
(2969, 3, 2),
(3266, 1, 12),
(3286, 5, 11),
(3389, 2, 8),
(3490, 1, 12),
(3499, 5, 11),
(3518, 1, 12),
(3664, 5, 11),
(3749, 4, 20),
(3774, 6, 9),
(3798, 1, 1),
(3894, 6, 9),
(3939, 2, 8),
(3977, 5, 11),
(3986, 1, 1),
(4115, 1, 1),
(4482, 3, 2),
(4489, 4, 20),
(4498, 3, 2),
(4556, 3, 2),
(4699, 4, 20),
(4768, 7, 7),
(5134, 3, 2),
(5235, 1, 1),
(5257, 1, 12),
(5261, 2, 8),
(5282, 1, 1),
(5300, 3, 2),
(5362, 3, 2),
(5385, 1, 12),
(5517, 3, 2),
(5540, 6, 9),
(5561, 3, 2),
(5582, 4, 20),
(5645, 1, 1),
(5646, 2, 8),
(5652, 1, 12),
(5692, 5, 11),
(5751, 3, 2),
(5762, 5, 11),
(5859, 7, 7),
(5919, 7, 7),
(6047, 4, 20),
(6088, 3, 2),
(6193, 4, 20),
(6331, 3, 2),
(6548, 4, 20),
(6638, 5, 11),
(6934, 2, 8),
(7021, 6, 9),
(7027, 1, 1),
(7071, 7, 7),
(7082, 7, 7),
(7169, 4, 20),
(7305, 2, 8),
(7397, 7, 7),
(7421, 2, 8),
(7474, 4, 20),
(7497, 1, 12),
(7548, 5, 11),
(7596, 4, 20),
(7598, 4, 20),
(7658, 1, 1),
(7739, 7, 7),
(7764, 1, 12),
(7834, 1, 1),
(7933, 1, 12),
(7949, 3, 2),
(7955, 1, 1),
(8036, 1, 12),
(8162, 2, 8),
(8273, 3, 2),
(8648, 7, 7),
(8738, 4, 20),
(8750, 2, 8),
(8833, 7, 7),
(8945, 4, 20);

-- --------------------------------------------------------

--
-- Struttura della tabella `storicoproduzione`
--

CREATE TABLE `storicoproduzione` (
  `IDproduzione` int(11) NOT NULL,
  `quantitaProdotta` int(11) DEFAULT NULL,
  `orarioFine` time DEFAULT NULL,
  `dataFine` date DEFAULT NULL,
  `IDdip` varchar(16) DEFAULT NULL,
  `IDprodotto` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `storicoproduzione`
--

INSERT INTO `storicoproduzione` (`IDproduzione`, `quantitaProdotta`, `orarioFine`, `dataFine`, `IDdip`, `IDprodotto`) VALUES
(1, 15, '17:19:40', '2021-05-01', 'efg456', 1),
(2, 20, '17:19:40', '2021-05-01', 'hil789', 3),
(7, 10, '17:20:36', '2021-05-01', 'fwke6', 7),
(8, 10, '17:20:36', '2021-05-01', 'hil789', 2),
(9, 5, '17:20:36', '2021-05-01', 'jhoe08', 6),
(11, 10, '12:04:55', '2021-05-04', 'efg456', 5),
(12, 13, '15:51:56', '2021-05-24', 'fwke6', 1),
(20, 15, '16:00:01', '2021-05-24', 'fwke6', 4);

--
-- Trigger `storicoproduzione`
--
DELIMITER $$
CREATE TRIGGER `checkCambio` BEFORE INSERT ON `storicoproduzione` FOR EACH ROW begin
	if ((select I1.spazioDisp from INVENTARIO I1 where I1.tipoProd=new.IDprodotto)<new.quantitaProdotta) then
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Non posso depositare tutte queste unità';
	end if;

	if not exists (select I.settoreDeposito from INVENTARIO I where I.tipoProd=new.IDprodotto) then
		insert into INVENTARIO(tipoProd, quantitaTot, spazioDisp) values (new.IDprodotto, 0, 200);
	end if;
end
$$
DELIMITER ;
DELIMITER $$
CREATE TRIGGER `nuovoProdotto` AFTER INSERT ON `storicoproduzione` FOR EACH ROW begin
	declare i int default 0;
	
	update INVENTARIO
	set quantitaTot=quantitaTot+new.quantitaProdotta, spazioDisp=200-quantitaTot
	where tipoProd=new.IDprodotto;
	
	while i<new.quantitaProdotta do
		insert into PRODOTTO values(FLOOR(RAND()*(8999-1000+1)+1000), new.IDprodotto, new.IDproduzione);
		set i=i+1;
	end while;
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `tipoprodotto`
--

CREATE TABLE `tipoprodotto` (
  `IDprodotto` int(11) NOT NULL,
  `nomeProdotto` varchar(255) DEFAULT NULL,
  `tipologia` varchar(9) NOT NULL,
  `costoPerUnita` float NOT NULL,
  `descrizione` varchar(255) NOT NULL,
  `img` varchar(255) NOT NULL,
  `nomeTrad` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `tipoprodotto`
--

INSERT INTO `tipoprodotto` (`IDprodotto`, `nomeProdotto`, `tipologia`, `costoPerUnita`, `descrizione`, `img`, `nomeTrad`) VALUES
(0, 'Mozzarella', 'latticino', 13, 'La mozzarella è un latticino[1] a pasta filata originario dell\'Italia meridionale e prodotta da secoli anche in Italia centrale; oggi la sua produzione è diffusa in tutta Italia e in vari paesi del mondo.', 'https://i.imgur.com/qhNdYkC.jpg', 'mozzarella'),
(1, 'Orzo', 'cereale', 2.5, 'L’orzo comune è la specie economicamente più importante tra quelle coltivate del genere Hordeum, quella da cui si ricava l\'orzo alimentare da cui dipende una considerevole parte dell\'alimentazione mondiale.', 'https://i.imgur.com/LIYpK4U.jpg', 'barley'),
(2, 'Ricotta', 'latticino', 3.3, 'La ricotta (dal latino recocta) è un prodotto caseario, più precisamente un latticino. La ricotta viene ottenuta attraverso la coagulazione delle proteine del siero di latte, cioè la parte liquida che si separa dalla cagliata durante la caseificazione.', 'https://i.imgur.com/SfvOSUk.jpg', 'ricotta'),
(3, 'Pecorino', 'latticino', 3, 'Il Pecorino Romano è un formaggio italiano a denominazione di origine protetta, la cui zona di origine comprende il Lazio e la provincia di Grosseto.', 'https://i.imgur.com/evyP8Ia.jpg', 'romano'),
(4, 'Frumento', 'cereale', 2, 'Il grano o frumento, arcaicamente anche trittico, è un genere della famiglia graminacee, cereale di antica coltura, la cui area d\'origine è localizzata tra Mar Mediterraneo, Mar Nero e Mar Caspio.', 'https://i.imgur.com/rzCB39C.jpg', 'wheat'),
(5, 'Granturco', 'cereale', 3.8, 'Il mais è una pianta erbacea annuale della famiglia delle Poaceae, tribù delle Maydeae: addomesticato dalle popolazioni indigene in Messico centrale in tempi preistorici circa 10.000 anni fa.', 'https://i.imgur.com/YN7ZKIO.jpg', 'corn'),
(6, 'Provola', 'latticino', 4.5, 'La provola è un formaggio di latte vaccino, a latte crudo e a pasta cotta o pasta semicotta e filata. In genere ha la forma di una sfera schiacciata, dal peso di circa mezzo chilo. Una provola ottenuta da latte bufalino viene prodotta in Campania.', 'https://i.imgur.com/Aw243VD.jpg', 'provolone'),
(7, 'Riso', 'cereale', 6.2, 'Il riso o risoide è un alimento costituito dalla cariosside prodotta da diverse piante dei generi Oryza e Zizania, opportunamente lavorata. Le più note specie utilizzate sono l\'Oryza sativa e l\'Oryza glaberrima.', 'https://i.imgur.com/s7TzyB8.jpg', 'rice');

--
-- Trigger `tipoprodotto`
--
DELIMITER $$
CREATE TRIGGER `insNewProd` AFTER INSERT ON `tipoprodotto` FOR EACH ROW begin
	insert into INVENTARIO(tipoProd, quantitaTot, spazioDisp) values (new.IDprodotto, 0, 200);
end
$$
DELIMITER ;

-- --------------------------------------------------------

--
-- Struttura della tabella `utente`
--

CREATE TABLE `utente` (
  `idUtente` int(11) NOT NULL,
  `username` varchar(255) DEFAULT NULL,
  `password` varchar(255) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `adminFlag` tinyint(1) DEFAULT 0,
  `pIvaCliente` varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dump dei dati per la tabella `utente`
--

INSERT INTO `utente` (`idUtente`, `username`, `password`, `email`, `adminFlag`, `pIvaCliente`) VALUES
(2, 'gigimix', '5f4dcc3b5aa765d61d8327deb882cf99', 'gigimix99@gmail.com', 0, '53806'),
(3, 'admin', '5f4dcc3b5aa765d61d8327deb882cf99', 'francesco.vattiato@hotmail.it', 1, 'abc123'),
(4, 'pippo123', '7c6a180b36896a0a8c02787eeafb0e4c', 'francesco12@gmail.com', 0, '6njfji06');

-- --------------------------------------------------------

--
-- Struttura per vista `oldbuy`
--
DROP TABLE IF EXISTS `oldbuy`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `oldbuy`  AS SELECT `o`.`IDordine` AS `IDordine`, `o`.`nDeposito` AS `nDeposito`, `o`.`IDcliente` AS `IDcliente`, `o`.`IDmagazz` AS `IDmagazz`, `o`.`quantitaRichiesta` AS `quantitaRichiesta`, `o`.`dataOrdine` AS `dataOrdine`, `o`.`costoTot` AS `costoTot`, `o`.`pagato` AS `pagato`, `i`.`settoreDeposito` AS `settoreDeposito`, `i`.`tipoProd` AS `tipoProd`, `i`.`quantitaTot` AS `quantitaTot`, `i`.`spazioDisp` AS `spazioDisp`, `tp`.`IDprodotto` AS `IDprodotto`, `tp`.`nomeProdotto` AS `nomeProdotto`, `tp`.`tipologia` AS `tipologia`, `tp`.`costoPerUnita` AS `costoPerUnita`, `tp`.`descrizione` AS `descrizione`, `tp`.`img` AS `img` FROM ((`ordine` `o` join `inventario` `i` on(`o`.`nDeposito` = `i`.`settoreDeposito`)) join `tipoprodotto` `tp` on(`tp`.`IDprodotto` = `i`.`tipoProd`)) ;

-- --------------------------------------------------------

--
-- Struttura per vista `prezzounitinv`
--
DROP TABLE IF EXISTS `prezzounitinv`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `prezzounitinv`  AS SELECT `i`.`settoreDeposito` AS `settD`, `tp`.`costoPerUnita` AS `costouni` FROM (`inventario` `i` join `tipoprodotto` `tp` on(`i`.`tipoProd` = `tp`.`IDprodotto`)) ;

--
-- Indici per le tabelle scaricate
--

--
-- Indici per le tabelle `cliente`
--
ALTER TABLE `cliente`
  ADD PRIMARY KEY (`pIva`);

--
-- Indici per le tabelle `dipendente`
--
ALTER TABLE `dipendente`
  ADD PRIMARY KEY (`CF`),
  ADD KEY `idx_pa` (`prodottoAttuale`);

--
-- Indici per le tabelle `inventario`
--
ALTER TABLE `inventario`
  ADD PRIMARY KEY (`settoreDeposito`),
  ADD UNIQUE KEY `tipoProd` (`tipoProd`),
  ADD KEY `idx_tp1` (`tipoProd`);

--
-- Indici per le tabelle `ordine`
--
ALTER TABLE `ordine`
  ADD PRIMARY KEY (`IDordine`),
  ADD KEY `idx_nDep` (`nDeposito`),
  ADD KEY `idx_idc` (`IDcliente`),
  ADD KEY `idx_mag` (`IDmagazz`);

--
-- Indici per le tabelle `prodotto`
--
ALTER TABLE `prodotto`
  ADD PRIMARY KEY (`nTimbro`,`tipoPr`,`prProdutt`),
  ADD KEY `idx_tipo` (`tipoPr`),
  ADD KEY `idx_prp` (`prProdutt`);

--
-- Indici per le tabelle `storicoproduzione`
--
ALTER TABLE `storicoproduzione`
  ADD PRIMARY KEY (`IDproduzione`),
  ADD KEY `idx_dip` (`IDdip`),
  ADD KEY `idx_pr` (`IDprodotto`);

--
-- Indici per le tabelle `tipoprodotto`
--
ALTER TABLE `tipoprodotto`
  ADD PRIMARY KEY (`IDprodotto`);

--
-- Indici per le tabelle `utente`
--
ALTER TABLE `utente`
  ADD PRIMARY KEY (`idUtente`),
  ADD KEY `idx_pivacli` (`pIvaCliente`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

--
-- AUTO_INCREMENT per la tabella `inventario`
--
ALTER TABLE `inventario`
  MODIFY `settoreDeposito` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT per la tabella `ordine`
--
ALTER TABLE `ordine`
  MODIFY `IDordine` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT per la tabella `storicoproduzione`
--
ALTER TABLE `storicoproduzione`
  MODIFY `IDproduzione` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;

--
-- AUTO_INCREMENT per la tabella `utente`
--
ALTER TABLE `utente`
  MODIFY `idUtente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Limiti per le tabelle scaricate
--

--
-- Limiti per la tabella `dipendente`
--
ALTER TABLE `dipendente`
  ADD CONSTRAINT `dipendente_ibfk_1` FOREIGN KEY (`prodottoAttuale`) REFERENCES `tipoprodotto` (`IDprodotto`);

--
-- Limiti per la tabella `inventario`
--
ALTER TABLE `inventario`
  ADD CONSTRAINT `inventario_ibfk_1` FOREIGN KEY (`tipoProd`) REFERENCES `tipoprodotto` (`IDprodotto`);

--
-- Limiti per la tabella `ordine`
--
ALTER TABLE `ordine`
  ADD CONSTRAINT `ordine_ibfk_1` FOREIGN KEY (`nDeposito`) REFERENCES `inventario` (`settoreDeposito`),
  ADD CONSTRAINT `ordine_ibfk_2` FOREIGN KEY (`IDcliente`) REFERENCES `cliente` (`pIva`),
  ADD CONSTRAINT `ordine_ibfk_3` FOREIGN KEY (`IDmagazz`) REFERENCES `dipendente` (`CF`);

--
-- Limiti per la tabella `prodotto`
--
ALTER TABLE `prodotto`
  ADD CONSTRAINT `prodotto_ibfk_1` FOREIGN KEY (`tipoPr`) REFERENCES `tipoprodotto` (`IDprodotto`),
  ADD CONSTRAINT `prodotto_ibfk_2` FOREIGN KEY (`prProdutt`) REFERENCES `storicoproduzione` (`IDproduzione`);

--
-- Limiti per la tabella `storicoproduzione`
--
ALTER TABLE `storicoproduzione`
  ADD CONSTRAINT `storicoproduzione_ibfk_1` FOREIGN KEY (`IDdip`) REFERENCES `dipendente` (`CF`),
  ADD CONSTRAINT `storicoproduzione_ibfk_2` FOREIGN KEY (`IDprodotto`) REFERENCES `tipoprodotto` (`IDprodotto`);

--
-- Limiti per la tabella `utente`
--
ALTER TABLE `utente`
  ADD CONSTRAINT `utente_ibfk_1` FOREIGN KEY (`pIvaCliente`) REFERENCES `cliente` (`pIva`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
