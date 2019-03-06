<?php

use CRM_Xerotweaks_ExtensionUtil as E;

class CRM_Xerotweaks_Map {
  // Mapping function for contact entities.
  public static function contact(&$data, &$params) {
    // Do not include the contact ID as part of the Xero Contact Name
    $params['Name'] = $data['display_name'];

    // Add supplemental Address lines.
    if (!empty($data['supplemental_address_1'])) {
      $params['Addresses']['Address'][0]['AddressLine2'] = $data['supplemental_address_1'];
    }
    if (!empty($data['supplemental_address_2'])) {
      $params['Addresses']['Address'][0]['AddressLine3'] = $data['supplemental_address_2'];
    }
    if (!empty($data['supplemental_address_3'])) {
      $params['Addresses']['Address'][0]['AddressLine4'] = $data['supplemental_address_3'];
    }
  }

  public static function invoice(&$data, &$params) {
    unset($params['Reference']);

    // Use the contribution source as the reference if available.
    if (!empty($data['contribution_source'])) {
      $params['Reference'] = $data['contribution_source'];
    }

    // Re-map each line item.
    $i = 0;
    foreach ($data['line_items'] as &$item) {
      static::line_item($item, $params['LineItems']['LineItem'][$i++]);
    }
  }

  public static function line_item(&$data, &$params) {
    // Use *only* the line item label
    $params['Description'] = $data['label'];
  }
}
