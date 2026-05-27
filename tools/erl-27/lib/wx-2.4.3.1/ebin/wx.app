%% This is an -*- erlang -*- file.
%%
%% %CopyrightBegin%
%%
%% Copyright Ericsson AB 2010-2023. All Rights Reserved.
%%
%% Licensed under the Apache License, Version 2.0 (the "License");
%% you may not use this file except in compliance with the License.
%% You may obtain a copy of the License at
%%
%%     http://www.apache.org/licenses/LICENSE-2.0
%%
%% Unless required by applicable law or agreed to in writing, software
%% distributed under the License is distributed on an "AS IS" BASIS,
%% WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
%% See the License for the specific language governing permissions and
%% limitations under the License.
%%
%% %CopyrightEnd%

{application, wx,
 [{description, "Yet another graphics system"},
  {vsn, "2.4.3.1"},
  {modules,
   [
    %% Generated modules
  wxIcon, wxColourPickerEvent, wxGraphicsMatrix, wxImage, wxGraphicsContext, wxPreviewFrame, wxComboBox, wxFontPickerCtrl, wxFileDialog, wxFlexGridSizer, wxPrintDialogData, wxScrolledWindow, wxMouseCaptureChangedEvent, wxDCOverlay, wxClipboardTextEvent, wxDisplay, wxChoicebook, wxSystemOptions, wxNotificationMessage, wxGridCellFloatRenderer, wxGridCellAttr, wxWindowDC, wxColourDialog, wxStatusBar, wxGenericDirCtrl, wxInitDialogEvent, wxEvent, wxPrintout, wxDateEvent, wxSysColourChangedEvent, wxScrollBar, wxLocale, wxBitmap, wxQueryNewPaletteEvent, wxSizerItem, wxGridCellBoolRenderer, wxPasswordEntryDialog, wxCommandEvent, wxFrame, wxNavigationKeyEvent, wxGraphicsRenderer, wxMouseCaptureLostEvent, wxTextEntryDialog, wxIdleEvent, wxStyledTextCtrl, wxListItem, wxSpinCtrl, wxGLCanvas, wxControlWithItems, wxMDIChildFrame, wxStdDialogButtonSizer, wxFontPickerEvent, wxPrintPreview, wxDirPickerCtrl, wxKeyEvent, wxBookCtrlBase, wxFontDialog, wxRadioBox, wxCalendarDateAttr, wxPaintEvent, wxGridCellEditor, wxTreebook, wxSizeEvent, wxLogNull, wxSetCursorEvent, wxChoice, wxControl, wxToggleButton, wxGraphicsFont, wxStaticText, wxJoystickEvent, wxPrinter, wxTextAttr, wxGridBagSizer, wxListbook, wxGridSizer, wxScrollEvent, wxGLContext, wxWindowCreateEvent, wxGridCellFloatEditor, wxStyledTextEvent, wxPrintDialog, wxStaticBox, wxBufferedDC, wxTextCtrl, wxClientDC, wxListCtrl, wxCalendarEvent, wxGauge, wxListView, wxSizerFlags, wxGridCellTextEditor, wxDataObject, wxShowEvent, wxBitmapDataObject, wxFindReplaceDialog, wxTextDataObject, wxStaticBitmap, wxGridCellRenderer, wxStaticLine, wxMiniFrame, wxListEvent, wxCursor, wxDialog, wxTopLevelWindow, wxPaintDC, wxTreeCtrl, wxScreenDC, wxFileDataObject, wxBitmapButton, wxPopupWindow, wxColourPickerCtrl, wxFilePickerCtrl, wxFindReplaceData, wxPostScriptDC, wxGrid, wxAuiSimpleTabArt, wxSashEvent, wxMask, wxFontData, wxSplitterEvent, wxWebView, wxBookCtrlEvent, wxCheckBox, wxHtmlWindow, wxPaletteChangedEvent, wxListItemAttr, wxMirrorDC, wxAuiManager, wxBoxSizer, wxClipboard, wxMouseEvent, wxDirDialog, wxMenu, wxMDIClientWindow, wxAuiPaneInfo, wxPrintData, wxSplitterWindow, wxProgressDialog, wxGridCellNumberEditor, wxListBox, wxActivateEvent, wxFileDirPickerEvent, wxMenuItem, wxChildFocusEvent, wxMessageDialog, wxButton, wxMenuBar, wxDisplayChangedEvent, wxToolBar, wxGraphicsPen, wxGridCellNumberRenderer, wxTreeEvent, wxArtProvider, wxHtmlEasyPrinting, wxBufferedPaintDC, wxRegion, wxMoveEvent, wxAuiManagerEvent, wxCalendarCtrl, wxEvtHandler, wxGraphicsGradientStops, wxContextMenuEvent, wxLayoutAlgorithm, wxCheckListBox, wxGridCellBoolEditor, wxMultiChoiceDialog, wxOverlay, wxTaskBarIconEvent, wxDropFilesEvent, wxPanel, wxGraphicsPath, wxSashWindow, wxFocusEvent, wxXmlResource, wxGridCellChoiceEditor, wxImageList, wxAuiNotebook, wxNotifyEvent, wxToolTip, wxPalette, wxSlider, wxSizer, wxGBSizerItem, wxPen, wxBrush, wxAuiNotebookEvent, wxMaximizeEvent, wxWebViewEvent, wxStaticBoxSizer, wxUpdateUIEvent, wxColourData, wxPageSetupDialogData, wxMemoryDC, wxToolbook, wxPopupTransientWindow, wxAcceleratorEntry, wxRadioButton, wxPickerBase, wxCloseEvent, wxNotebook, wxAcceleratorTable, wxDC, wxCaret, wxIconizeEvent, wxMenuEvent, wxGraphicsBrush, wxMDIParentFrame, wxPreviewControlBar, wxHelpEvent, wxFont, wxEraseEvent, wxDatePickerCtrl, wxSystemSettings, wxWindow, wxHtmlLinkEvent, wxSplashScreen, wxSpinEvent, wxSingleChoiceDialog, wxTaskBarIcon, wxAuiTabArt, wxIconBundle, wxSpinButton, wxPageSetupDialog, wxSashLayoutWindow, wxGraphicsObject, wxGridEvent, wx_misc, wxGridCellStringRenderer, wxPreviewCanvas, wxGCDC, wxScrollWinEvent, wxAuiDockArt, wxWindowDestroyEvent, glu, gl,
    %% Handcrafted modules
    wx,
    wx_object,
    wxe_master,
    wxe_server,
    wxe_util
   ]},
  {registered, []},
  {applications, [stdlib, kernel]},
  {env, []},
  {runtime_dependencies, ["stdlib-5.0","kernel-8.0","erts-12.0"]}
 ]}.
