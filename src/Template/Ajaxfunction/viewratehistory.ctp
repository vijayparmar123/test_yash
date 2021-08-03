<table class='table-bordered'>
    <tr>
        <th>Index</th>
        <th>PO/LOI Date</th>
        <th>PO/LOI No</th>
        <th>Vendor Name</th>
        <th>Quantity</th>
        <th>Unit</th>
        <th>Unit Rate</th>
        <th>Discount</th>
        <th>GST</th>
        <th>Amount</th>
        <th>Final Rate</th>
        <th>View Button</th>
    </tr>
    <?php
$i = 1;
if($result != '') {
    foreach ($result as $data) {
        if (isset($data["erp_inventory_po_detail"])) {
            $data = array_merge($data, $data["erp_inventory_po_detail"]);
        }

        $fetchedDate = strtotime($data['po_date']);
        $date_formated = date('d-m-Y', $fetchedDate);
        // debug($retrivedData); ?>
        <tr id="tr_<?php echo $i; ?>">
            <td><?php echo $i; ?></td>
            <td style="min-width:10px;"><?php echo $date_formated; ?></td>
            <td><?php echo $data['po_no']; ?></td>
            <td><?php echo $this->ERPfunction->get_vendor_name_by_code($data['vendor_userid']); ?></td>
            <td><?php echo $data['quantity']; ?></td>
            <!-- Fetch rate from ERPfunctionHelper function called get_items_units   -->
            <td><?php echo $this->ERPfunction->get_items_units($data["material_id"]); ?></td>
            <td><?php echo $data['unit_price']; ?></td>
            <td><?php echo $data['discount']; ?></td>
            <td><?php echo $data['gst']; ?></td>
            <td><?php echo $data['amount']; ?></td>
            <td><?php echo $data['single_amount']; ?></td>
            <?php
                if (date('Y-m-d', strtotime($data['po_date'])) > date('Y-m-d', strtotime('01-07-2017'))) {
            ?>
            <td>
                <?php
                    echo "<a href='{$this->request->base}/inventory/previewpo2/{$data["po_id"]}' target='_blank' class='btn btn-sm btn-primary'>View</a>";
                ?>
            </td>
            <?php
                } else {
            ?>
            <td>
                <?php
                    echo "<a href='{$this->request->base}/inventory/previewpo/{$data["po_id"]}' target='_blank' class='btn btn-sm btn-primary'>View</a>";
                ?>
            </td>
            <?php
                }
            ?>
            <?php $i++; ?>
        </tr>
        <?php }
        }else {
            echo "No Records Found...";
        }
        ?>
</table>