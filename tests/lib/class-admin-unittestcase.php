<?php

namespace Campus\A11y\Test\Admin;

use Campus\A11y\Test\UnitTestCase as UTC;
use Campus\A11y\Test\Mock;

class UnitTestCase extends UTC {
	use Mock\RoleMocker;
	use Mock\AdminMocker;

	public function has_menu_page( $page ) {
		global $menu;
		if ( ! is_array( $menu ) ) {
			return false;
		}
		$present = false;
		foreach ( $menu as $item ) {
			if ( $page === $item[2] ) {
				$present = true;
				break;
			}
		}
		return $present;
	}

	public function expects_menu_page( $page ) {
		if ( ! $this->has_menu_page( $page ) ) {
			$this->fail( "menu {$page} not added" );
		}
	}

	public function expects_no_menu_page( $page ) {
		if ( $this->has_menu_page( $page ) ) {
			$this->fail( "menu {$page} is added" );
		}
	}

	public function has_submenu_page( $menu, $page ) {
		global $submenu;
		if ( ! is_array( $submenu ) ) {
			return false;
		}
		if ( ! array_key_exists( $menu, $submenu ) ) {
			return false;
		}
		$present = false;
		foreach ( $submenu[ $menu ] as $item ) {
			if ( $page === $item[2] ) {
				$present = true;
				break;
			}
		}
		return $present;
	}

	public function expects_submenu_page( $menu, $page ) {
		if ( ! $this->has_submenu_page( $menu, $page ) ) {
			$this->fail( "page {$page} not added to menu {$menu}" );
		}
	}

	public function expects_no_submenu_page( $menu, $page ) {
		if ( $this->has_submenu_page( $menu, $page ) ) {
			$this->fail( "page {$page} is added to menu {$menu}" );
		}
	}
}
