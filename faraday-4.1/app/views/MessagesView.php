<? $this->load->view('MessagesSubView', array(messages => $messages['error'], type => 'danger')) ?>
<? $this->load->view('MessagesSubView', array(messages => $messages['info'], type => 'info')) ?>
<? $this->load->view('MessagesSubView', array(messages => $messages['warn'], type => 'warning')) ?>
<? $this->load->view('MessagesSubView', array(messages => $messages['success'], type => 'success')) ?>