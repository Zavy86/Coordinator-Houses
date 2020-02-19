--
-- Houses - Setup (1.0.0)
--
-- @package Coordinator\Modules\Houses
-- @company Cogne Acciai Speciali s.p.a
-- @authors Manuel Zavatta <manuel.zavatta@cogne.com>
--

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 0;
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";

-- --------------------------------------------------------



-- --------------------------------------------------------

--
-- Authorizations
--

INSERT IGNORE INTO `framework__modules__authorizations` (`id`,`fkModule`,`order`) VALUES
('houses-manage','houses',1),
('houses-houses_view','houses',2),
('houses-houses_manage','houses',3);

-- --------------------------------------------------------

SET FOREIGN_KEY_CHECKS = 1;

-- --------------------------------------------------------