<script>

    <?php if(isset($gender)) { ?>
    function genderDD(genderId= '',select="#gender_id") {
        $(select).select2({
            ajax: {
                url: "<?= site_url('Gender/getGenderDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
                        gender_id : genderId,
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
    <?php } ?>

	<?php if(isset($employeeName)) { ?>
	function employeeNameDD(empId= '',select="#emp_dropdown",value = 'false',ids = []) {
		$(select).select2({
			ajax: {
				url: "<?= site_url('employee/getEmployeeListDD') ?>",
				dataType: 'json',
				type: 'post',
				delay: 250,
				data: function (params) {
					return {
						filter_param: params.term || '',
						emp_id : empId,
						page: params.page || 1,
						all_employee : value,
						emp_ids : ids,
						ex_emp : $("#ex_emp").val()
					};
				},
				processResults: function (data, params) {
					console.log("emp"+ data);
					return {
						results: data.result,
						pagination: {
							more: (data.page * 10) < data.totalRows

						}
					};
				}
			},
			placeholder: 'Search For Your Employee',
			escapeMarkup: function (markup) {
				return markup;
			}
		}).on('select2:select', function () {
			if ($("#" + $(this).attr('id')).valid()) {
				$("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
			}
		});
	}
	<?php } ?>

	<?php if(isset($type)) { ?>
	function typeDD(typeId= '',select="#type_id") {
		$(select).select2({
			ajax: {
				url: "<?= site_url('EmployeeType/getTypeDD') ?>",
				dataType: 'json',
				type: 'post',
				delay: 250,
				data: function (params) {
					return {
						filter_param: params.term || '',
						type_id : typeId,
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
			placeholder: 'Search For Your Type',
			escapeMarkup: function (markup) {
				return markup;
			}
		}).on('select2:select', function () {
			if ($("#" + $(this).attr('id')).valid()) {
				$("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
			}
		});
	}
	<?php } ?>

	<?php if(isset($employeeName)) { ?>
    function nameEmployeeDD(empId= '',select="#emp_id") {
        $(select).select2({
            ajax: {
                url: "<?= site_url('Employee/getEmployeeListDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
						emp_id : empId,
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
            placeholder: 'Search For Your Employee',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
			if ($("#" + $(this).attr('id')).valid()) {
				$("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
			}
        });
    }
    <?php } ?>

	<?php if(isset($project)) { ?>
	function projectDD(projectId= '',select="#project_id",value='false') {
		$(select).select2({
			ajax: {
				url: "<?= site_url('Project/getProjectDD') ?>",
				dataType: 'json',
				type: 'post',
				delay: 250,
				data: function (params) {
					return {
						filter_param: params.term || '',
						project_id : projectId,
						all_project : value,
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
			placeholder: 'Search For Your Project',
			escapeMarkup: function (markup) {
				return markup;
			}
		}).on('select2:select', function () {
			if ($("#" + $(this).attr('id')).valid()) {
				$("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
			}
		});
	}
	<?php } ?>

   <?php if(isset($teamProject)) { ?>
	function teamProjectDD(projectId= '',select="#project_id",value='false') {
		$(select).select2({
			ajax: {
				url: "<?= site_url('Project/getTeamProjectDD') ?>",
				dataType: 'json',
				type: 'post',
				delay: 250,
				data: function (params) {
					return {
						filter_param: params.term || '',
						project_id : projectId,
						all_project : value,
						project_head : $("#emphead").val(),
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
			placeholder: 'Search For Your Team Project',
			escapeMarkup: function (markup) {
				return markup;
			}
		}).on('select2:select', function () {
			if ($("#" + $(this).attr('id')).valid()) {
				$("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
			}
		});
	}
	<?php } ?>


	<?php if(isset($task)) { ?>
	function taskDD(taskId= '',select="#task_id",value = 'false') {
		$(select).select2({
			ajax: {
				url: "<?= site_url('Task/getTaskDD') ?>",
				dataType: 'json',
				type: 'post',
				delay: 250,
				data: function (params) {
					return {
						filter_param: params.term || '',
						task_id : taskId,
						all_task : value,
						project_id: $("#project_id").val(),
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
			placeholder: 'Search For Your Task',
			escapeMarkup: function (markup) {
				return markup;
			}
		}).on('select2:select', function () {
			if ($("#" + $(this).attr('id')).valid()) {
				$("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
			}
		});
	}
	<?php } ?>

	<?php if(isset($employeeShift)) { ?>
    function employeeShiftDD(shiftId= '',select="#shift_id") {
        $(select).select2({
            ajax: {
                url: "<?= site_url('EmployeeShift/getEmployeeShiftDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
						shift_id : shiftId,
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
            placeholder: 'Search For Your Shift',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }
    <?php } ?>

    <?php if(isset($maritalStatus)) { ?>
    function maritalStatusDD(maritalStatusId= '',select="#marital_status_id") {
        $(select).select2({
            ajax: {
                url: "<?= site_url('MaritalStatus/getMaritalStatusDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
                        marital_status_id : maritalStatusId,
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
    <?php } ?>


	<?php if(isset($country)) { ?>
    function countryDD(countryId= '',select="#country_id") {
        $(select).select2({
            ajax: {
                url: "<?= site_url('Country/getCountryDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
						country_id : countryId,
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
            placeholder: 'Search For Your Country',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }
    <?php } ?>

	<?php if(isset($accountDetails)) { ?>
    function accountDetailsDD(accountDetailsId= '',select="#account_details_id") {
        $(select).select2({
            ajax: {
                url: "<?= site_url('AccountDetails/getAccountDetailsDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
						account_details_id : accountDetailsId,
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
            placeholder: 'Search For Your Account Details',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }
    <?php } ?>

	<?php if(isset($department)) { ?>
    function departmentDD(departmentId= '',select="#department_id") {
        $(select).select2({
            ajax: {
                url: "<?= site_url('Department/getDepartmentDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
						department_id : departmentId,
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
            placeholder: 'Search For Your Department',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }
    <?php } ?>

	<?php if(isset($salary)) { ?>
    function salaryDD(salaryId= '',select="#salary_id") {
        $(select).select2({
            ajax: {
                url: "<?= site_url('Salary/getSalaryDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
						salary_id : salaryId,
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
            placeholder: 'Search For Your Salary',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }
    <?php } ?>

	<?php if(isset($role)) { ?>
    function roleDD(roleId= '',select="#role_id") {
        $(select).select2({
            ajax: {
                url: "<?= site_url('Role/getRoleDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
						role_id : roleId,
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
            placeholder: 'Search For Your Role',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }
    <?php } ?>

	<?php if(isset($workWeek)) { ?>
    function workWeekDD(workWeekId= '',select="#work_week_id") {
        $(select).select2({
            ajax: {
                url: "<?= site_url('WorkWeek/getWorkWeekDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
						work_week_id : workWeekId,
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
            placeholder: 'Search For Your Work Week',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }
    <?php } ?>

	<?php if(isset($company)) { ?>
    function companyDD(companyId= '',select="#company_id") {
        $(select).select2({
            ajax: {
                url: "<?= site_url('Company/getCompanyDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
						company_id : companyId,
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
            placeholder: 'Search For Your Work Week',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }
    <?php } ?>

	<?php if(isset($state)) { ?>
	function stateDD(stateId= '',select="#state_id") {
		$(select).select2({
			ajax: {
				url: "<?= site_url('State/getStateDD') ?>",
				dataType: 'json',
				type: 'post',
				delay: 250,
				data: function (params) {
					return {
						filter_param: params.term || '',
						country_id: $("#country_id").val(),
						state_id: "<?= isset($getMemberData['country']) && ($getMemberData['country'] != '') ? $getMemberData['country'] : 0 ?>"
						// page: params.page || 1
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
	<?php } ?>

    <?php if(isset($city)) { ?>
    function cityDD(cityId= '',select="#city_id") {
        $(select).select2({
            ajax: {
                url: "<?= site_url('City/getCityDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
                        state_id: $("#state_id").val(),
                        city_id: "<?= isset($getMemberData['city']) && ($getMemberData['city'] != '') ? $getMemberData['city'] : 0 ?>"
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
    <?php } ?>

	<?php if(isset($leaveType)) { ?>
    function leaveTypeDD(leaveTypeId= '',select="#leave_type_id") {
        $(select).select2({
            ajax: {
                url: "<?= site_url('LeaveType/getLeaveTypeDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
						leave_type_id : leaveTypeId,
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
            placeholder: 'Search For Your Leave Type',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }
    <?php } ?>

	<?php if(isset($leaveReason)) { ?>
    function leaveReasonDD(leaveReasonId= '',select="#leave_reason_id") {
        $(select).select2({
            ajax: {
                url: "<?= site_url('LeaveReason/getLeaveReasonDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
						leave_type_id : leaveReasonId,
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
            placeholder: 'Search For Your Leave Reason',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }
    <?php } ?>

	<?php if(isset($designation)) { ?>
    function designationDD(designationId= '',select="#designation_id") {
        $(select).select2({
            ajax: {
                url: "<?= site_url('Designation/getDesignationDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
						designation_id : designationId,
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
            placeholder: 'Search For Your Designation',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }
    <?php } ?>

	<?php if(isset($employeeTypeFilter)) {  ?>
    function employeeTypeFilterDD(employeeTypeFilterId= '',select="#employeeTypeFilterId") {
        $(select).select2({
            ajax: {
                url: "<?= site_url('Employee/getemployeeTypeFilterDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
						employeeTypeFilter_id : employeeTypeFilterId,
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
            placeholder: 'Search For Employee Type',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }
    <?php } ?>

	<?php if(isset($leaveReasonFilter)) {  ?>
    function leaveReasonFilterDD(leaveReasonFilterId= '',select="#leaveReasonFilter_id") {
        $(select).select2({
            ajax: {
                url: "<?= site_url('TimeSheet/getLeaveReasonFilterDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
						leaveReasonFilter_id : leaveReasonFilterId,
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
            placeholder: 'Search For Leave Reason',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }
    <?php } ?>
	
	
	<?php if(isset($teamHead)) { ?>
	function teamHeadDD(empId= '',select="#teamhead_dropdown") {
		$(select).select2({
			ajax: {
				url: "<?= site_url('employee/getTeamHeadListDD') ?>",
				dataType: 'json',
				type: 'post',
				delay: 250,
				data: function (params) {
					return {
						filter_param: params.term || '',
						emp_id : empId,
						page: params.page || 1
					};
				},
				processResults: function (data, params) {
					console.log("emp"+ data);
					return {
						results: data.result,
						pagination: {
							more: (data.page * 10) < data.totalRows

						}
					};
				}
			},
			placeholder: 'Search For Team Head',
			escapeMarkup: function (markup) {
				return markup;
			}
		}).on('select2:select', function () {
			if ($("#" + $(this).attr('id')).valid()) {
				$("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
			}
		});
	}
	<?php } ?>


	<?php if(isset($teamMeambers)) { ?>
	function teamMembersDD(empId= '',select="#teammembers_dropdown") {
		$(select).select2({
			ajax: {
				url: "<?= site_url('employee/getTeamMembersListDD') ?>",
				dataType: 'json',
				type: 'post',
				delay: 250,
				data: function (params) {
					return {
						filter_param: params.term || '',
						emp_id : empId,
						teamhead_id: $("#emphead").val(),
						page: params.page || 1
					};
				},
				processResults: function (data, params) {
					console.log("emp"+ data);
					return {
						results: data.result,
						pagination: {
							more: (data.page * 10) < data.totalRows

						}
					};
				}
			},
			placeholder: 'Search For Team Members',
			escapeMarkup: function (markup) {
				return markup;
			}
		}).on('select2:select', function () {
			if ($("#" + $(this).attr('id')).valid()) {
				$("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
			}
		});
	}
	<?php } ?>
	
	
	<?php if(isset($TLMembers)) { ?>
	function TLMembersDD(empId= '',select="#teammembers_dropdown") {
		$(select).select2({
			ajax: {
				url: "<?= site_url('Employee/getTLMembersDD') ?>",
				dataType: 'json',
				type: 'post',
				delay: 250,
				data: function (params) {
					return {
						filter_param: params.term || '',
						emp_id : empId,
						team_head_id: $("#emphead").val(),
						page: params.page || 1
					};
				},
				processResults: function (data, params) {
					console.log("emp"+ data);
					return {
						results: data.result,
						pagination: {
							more: (data.page * 10) < data.totalRows

						}
					};
				}
			},
			placeholder: 'Search For Team Members',
			escapeMarkup: function (markup) {
				return markup;
			}
		}).on('select2:select', function () {
			if ($("#" + $(this).attr('id')).valid()) {
				$("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
			}
		});
	}
	<?php } ?>
	
	
	<?php if(isset($leaveStatus)) {  ?>
    function leaveStatusDD(leaveStatusId= '',select="#leaveStatusId") {
        $(select).select2({
            ajax: {
                url: "<?= site_url('Report/getLeaveStatusDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
						leaveStatusid : leaveStatusId,
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
            placeholder: 'Search Leave Status',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }
    <?php } ?>
	
	
	
	<?php if(isset($monthlyWorkingDays)) { ?>
    function monthlyWorkingDaysDD(monthlyWorkingDaysId= '',select="#monthly_working_days") {
        $(select).select2({
            ajax: {
                url: "<?= site_url('MonthlyWeekDays/getMonthlyWeekDaysDD') ?>",
                dataType: 'json',
                type: 'post',
                delay: 250,
                data: function (params) {
                    return {
                        filter_param: params.term || '',
						monthly_working_days_id : monthlyWorkingDaysId,
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
            placeholder: 'Search For Your Working Days Title',
            escapeMarkup: function (markup) {
                return markup;
            }
        }).on('select2:select', function () {
            if ($("#" + $(this).attr('id')).valid()) {
                $("#" + $(this).attr('id')).removeClass('invalid').addClass('success');
            }
        });
    }
    <?php } ?>

</script>
