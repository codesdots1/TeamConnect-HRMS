<?php
if (isset($filters) && count($filters) > 0) { ?>
    <div class="panel panel-white panel-collapsed">
        <div class="panel-heading">
            <h5 class="panel-title"><?= lang('filters') ?>
                <a class="heading-elements-toggle"><i class="icon-more"></i></a>
            </h5>
            <div class="heading-elements">
                <ul class="icons-list">
                    <li><a data-action="collapse"></a></li>
                </ul>
            </div>
        </div>

        <div class="panel-body">
            <form action="" id="advanceFilter" name="advanceFilter">
                <input type="hidden" name="submit_btn" id="submit_btn" value="false">
                <?php
                foreach ($filters as $key => $filter) {
                    $class = isset($filter['class']) ? $filter['class'] : '';
                    $groupBy = isset($filter['group_by'])? $filter['group_by'] : "";
                    $selected = isset($filter['selected'])? $filter['selected'] : "";

                if ($key % 3 == 0) {
                if ($key != 0) {
                    echo "</div>";
                }
                ?>
                <div class="row">
                    <?php } ?>
                    <div class="col-md-4">
                        <div class="form-group">
                            <?php
                            switch ($filter['type']) {
                                case 'multi_select':
                                    ?>
                                    <label class="display-block"><?= lang($filter['title']) ?></label>
                                    <select class="<?= isset($filter['dynamic']) && $filter['dynamic'] ? '' : 'select' ?>" id="<?= $filter['id'] ?>"
                                            name="<?= $filter['name'] ?>" multiple="multiple" data-placeholder="Select <?= lang($filter['title']) ?>">
                                            <?= isset($filter['dynamic']) && $filter['dynamic'] ? '' : CreateOptions("html", $filter['tbl_name'], $filter['columns'], '','','','',$groupBy) ?>
                                    </select>
                                    <?php
                                    break;
                                case 'daterange':
                                    ?>
                                    <label class="display-block"><?= lang($filter['title']) ?></label>
                                    <input type="text" id="<?= $filter['name'] ?>" name="<?= $filter['name'] ?>" readonly class="btn btn-default daterange-predefined">
                                    <?php
                                    break;

                                case 'radio':
                                    ?>
                                    <?= lang($filter['main_title']) ?>
                                    <div class="checkbox form-group">
                                        <label class="radio-inline">
                                            <input type="radio" id="<?= $filter['id'] ?>" name="<?= $filter['name'] ?>"
                                                   value="<?= isset($filter['value']) ? $filter['value'] : ""; ?>"
                                                <?= ($selected != '') ? "checked" : ""; ?>
                                                   class="<?= $class; ?>">
                                            <?= lang($filter['title']) ?>
                                        </label>
                                    </div>
                                    <?php
                                    break;
                            }
                            ?>
                        </div>
                    </div>
                    <?php
                    if ($key == count($filters) - 1) {
                        echo "</div>";
                    }
                    }
                    ?>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group pull-left mt-5">
                                <button type="button" class="btn btn-xs border-blue-400 text-blue-400 btn-flat btn-icon  filter_data">
                                    <i class="icon-filter3"></i> <?= lang('filter');?>
                                </button>
                            </div>
                            <div class="form-group pull-left ml-5 mt-5">
                                <button type="reset" class="btn btn-xs border-danger-400 text-danger-400 btn-flat btn-icon  clear_filter">
                                    <i class="icon-reset"></i> <?= lang('reset');?>
                                </button>
                            </div>
                        </div>
                        <div class="col-md-4">
                        </div>
                    </div>
            </form>
        </div>
    </div>
    <?php
}
?>
<script>
    $(document).ready(function(){
        $('#date').val('');
        $('.select').select2();

        samajDD();
        businessTypeDD();
        surnameDD();
        genderDD();
        maritalStatusDD();
        nativePlaceDD();
        educationDD();
        monkDD();
        memberDD();
        categoryDD();
        stateDD();
        cityDD();
    });

    function samajDD() {
        $('#samaj_id').select2({
            ajax: {
                url: "<?= site_url('Samaj/getSamajDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: data.result,
                        pagination: {
                            more: (data.page * 10) < data.totalRows
                        }
                    };
                }
            },
            placeholder: 'Search For Your Samaj',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }

    function businessTypeDD() {
        $('#business_type_id').select2({
            ajax: {
                url: "<?= site_url('BusinessType/getBusinessTypeDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: data.result,
                        pagination: {
                            more: (data.page * 10) < data.totalRows
                        }
                    };
                }
            },
            placeholder: 'Search For Your Business Type',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }

    function surnameDD() {
        $('#surname_id').select2({
            ajax: {
                url: "<?= site_url('Surname/getSurnameDD') ?>",
                dataType: 'json',
                type: 'post',
                data: function (params) {
                    return {
                        filter_param: params.term || ''
                    };
                },
                processResults: function (data) {
                    return {
                        results: data
                    };
                }
            }
        });
    }

    function genderDD() {
        $('#gender_id').select2({
            ajax: {
                url: "<?= site_url('Gender/getGenderDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: data.result,
                        pagination: {
                            more: (data.page * 10) < data.totalRows
                        }
                    };
                }
            },
            placeholder: 'Search For Your Gender',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }

    function maritalStatusDD() {
        $('#marital_status_id').select2({
            ajax: {
                url: "<?= site_url('MaritalStatus/getMaritalStatusDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: data.result,
                        pagination: {
                            more: (data.page * 10) < data.totalRows
                        }
                    };
                }
            },
            placeholder: 'Search For Your Marital Status',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }

    function nativePlaceDD() {
        $('#native_id').select2({
            ajax: {
                url: "<?= site_url('Native/getNativeDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
                        samaj_id: $("#samaj_id").val(),
                        is_filter : 1,
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: data.result,
                        pagination: {
                            more: (data.page * 10) < data.totalRows
                        }
                    };
                }
            },
            placeholder: 'Search For Your Native Place',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }

    function educationDD() {
        $('#education_id').select2({
            ajax: {
                url: "<?= site_url('Education/getEducationDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
                        samaj_id: $("#samaj_id").val(),
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: data.result,
                        pagination: {
                            more: (data.page * 10) < data.totalRows
                        }
                    };
                }
            },
            placeholder: 'Search For Your Education',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }

    function monkDD() {
        $('#monk_location_id').select2({
            ajax: {
                url: "<?= site_url('Monk/getMonkDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: data.result,
                        pagination: {
                            more: (data.page * 10) < data.totalRows
                        }
                    };
                }
            },
            placeholder: 'Search For Your Monk',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }

    function memberDD() {
        $('#member_id').select2({
            ajax: {
                url: "<?= site_url('Member/getMemberDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: data.result,
                        pagination: {
                            more: (data.page * 10) < data.totalRows
                        }
                    };
                }
            },
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }

    function categoryDD() {
        $("#category_id").select2({
            ajax: {
                url: "<?= site_url('Category/getCategoryDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    var categoryId = $('#category_id').val();
                    return {
                        filter_param: params.term || '', // search term
                        page: params.page || 1,
                        category_id: categoryId
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: data.result,
                        pagination: {
                            more: (data.page * 10) < data.totalRows
                        }
                    };
                }
            },
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }


    function stateDD() {
        $('#state_id').select2({
            ajax: {
                url: "<?= site_url('State/getStateDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    var stateId = $('#state_id').val();
                    return {
                        filter_param: params.term || '',
                        state_id : stateId,
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: data.result,
                        pagination: {
                            more: (data.page * 10) < data.totalRows
                        }
                    };
                }
            },
            placeholder: 'Search For Your State',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }


    function cityDD() {
        $('#city_id').select2({
            ajax: {
                url: "<?= site_url('City/getCityDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    var stateId = $('#state_id').val();
                    var cityId = $('#city_id').val();
                    return {
                        filter_param: params.term || '',
                        state_id : stateId,
                        city_id : cityId,
                        page: params.page || 1
                    };
                },
                processResults: function (data, params) {
                    return {
                        results: data.result,
                        pagination: {
                            more: (data.page * 10) < data.totalRows
                        }
                    };
                }
            },
            placeholder: 'Search For Your City',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }
</script>
