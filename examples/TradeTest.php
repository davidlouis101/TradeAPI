<?php

/*
 * TradeAPI, simple to provide Trade UI V2.
 * Copyright (C) 2020  Organic (nnnlog)
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

/**
 * @name Trade
 * @main       nlog\TradeTest
 * @author     CrowBaldeYT
 * @api        4.0.0
 * @version    3.0.1
 * @softDepend TradeAPI
 */

namespace nlog;

use nlog\trade\merchant\MerchantRecipe;
use nlog\trade\merchant\MerchantRecipeList;
use nlog\trade\merchant\TraderProperties;
use nlog\trade\TradeAPI;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginCommand;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class TradeTest extends PluginBase {

	/** @var MerchantRecipeList */
	private $recipes;

	public function onEnable() {
		$this->getServer()->getCommandMap()->register("trade", new PluginCommand("trade", $this, $this));
		$this->recipes = new MerchantRecipeList(
				new MerchantRecipe(ItemFactory::get(ItemIds::ROTTEN_FLESH, 0, 32), ItemFactory::get(ItemIds::EMERALD), null, 0),
				new MerchantRecipe(ItemFactory::get(ItemIds::GOLD_NUGGET, 0, 32), ItemFactory::get(ItemIds::EMERALD), null, 0),
				new MerchantRecipe(ItemFactory::get(ItemIds::COAL, 0, 16), ItemFactory::get(ItemIds::EMERALD), null, 0),
				new MerchantRecipe(ItemFactory::get(ItemIds::DIAMOND, 0, 16), ItemFactory::get(ItemIds::EMERALD)->setCustomName("§bTrade"), null, 1),
				new MerchantRecipe(ItemFactory::get(ItemIds::EMERALD, 0, 52), ItemFactory::get(ItemIds::ENCHANTED_BOOK)->addEnchantment(new EnchantmentInstance(Enchantment::PROTECTION(), 4)), ItemFactory::get(ItemIds::BOOK), 2),
				new MerchantRecipe(ItemFactory::get(ItemIds::EMERALD, 0, 64), ItemFactory::get(ItemIds::BOOK)->addEnchantment(new EnchantmentInstance(Enchantment::MENDING(), 0)), ItemFactory::get(ItemIds::BOOK), 3),
		);
	}

	public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool {
		if ($sender instanceof Player && $sender->isOp()) {
			$prop = new TraderProperties();
			$prop->maxTradeTier = 3;
			$prop->tradeTier = 2;
			$prop->traderName = "§7[§a§lSchwarz Markt§r§7]§r ";
			$prop->xp = intval($args[0] ?? mt_rand(0, 50));

			TradeAPI::getInstance()->sendWindow($sender, $this->recipes, $prop);
		}

		return true;
	}
}
