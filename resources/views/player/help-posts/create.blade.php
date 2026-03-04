<x-app-layout>
    <x-slot name="title">Create Help Post</x-slot>

    <div>
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px;">
            <h2 style="font-size: 1.25rem; font-weight: 600;">Create Help Post</h2>
            <a href="{{ route('player.help-posts.index') }}" class="text-indigo-600 hover:text-indigo-900 text-sm">
                ← Back to My Posts
            </a>
        </div>

        <div class="info-card" style="padding: 24px;">
            <form method="POST" action="{{ route('player.help-posts.store') }}" enctype="multipart/form-data">
                @csrf

                {{-- Title --}}
                <div style="margin-bottom: 20px;">
                    <label for="title" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 6px;">Post Title</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                           style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 0.875rem; outline: none;"
                           placeholder="e.g. Need support for cricket gear" required>
                    @error('title')
                        <p style="color: #DC2626; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Description --}}
                <div style="margin-bottom: 20px;">
                    <label for="description" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 6px;">Description</label>
                    <textarea name="description" id="description" rows="6"
                              style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 0.875rem; outline: none; resize: vertical;"
                              placeholder="Describe your situation and what kind of help you need..." required>{{ old('description') }}</textarea>
                    <p style="font-size: 0.75rem; color: #9CA3AF; margin-top: 4px;">Maximum 5000 characters</p>
                    @error('description')
                        <p style="color: #DC2626; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Proof Document --}}
                <div style="margin-bottom: 24px;">
                    <label for="proof_document" style="display: block; font-size: 0.875rem; font-weight: 500; color: #374151; margin-bottom: 6px;">
                        Proof Document <span style="color: #9CA3AF; font-weight: 400;">(optional)</span>
                    </label>
                    <input type="file" name="proof_document" id="proof_document"
                           accept=".pdf,.jpg,.jpeg,.png,.doc,.docx"
                           style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 0.875rem; background: #F9FAFB;">
                    <p style="font-size: 0.75rem; color: #9CA3AF; margin-top: 4px;">
                        Accepted formats: PDF, JPG, PNG, DOC, DOCX. Max size: 5MB
                    </p>
                    @error('proof_document')
                        <p style="color: #DC2626; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Contact Details Section --}}
                <div style="background: #F9FAFB; border: 1px solid #E5E7EB; border-radius: 8px; padding: 18px; margin-bottom: 24px;">
                    <p style="font-size: 0.875rem; font-weight: 600; color: #374151; margin-bottom: 14px;">📞 Contact Details <span style="color: #9CA3AF; font-weight: 400; font-size: 0.8rem;">(so people can reach you)</span></p>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 14px;">
                        {{-- Contact Number --}}
                        <div>
                            <label for="contact_number" style="display: block; font-size: 0.8rem; font-weight: 500; color: #374151; margin-bottom: 6px;">Contact Number</label>
                            <input type="text" name="contact_number" id="contact_number" value="{{ old('contact_number') }}"
                                   style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 0.875rem; outline: none;"
                                   placeholder="e.g. +94 77 123 4567">
                            @error('contact_number')
                                <p style="color: #DC2626; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Contact Email --}}
                        <div>
                            <label for="contact_email" style="display: block; font-size: 0.8rem; font-weight: 500; color: #374151; margin-bottom: 6px;">Email Address</label>
                            <input type="email" name="contact_email" id="contact_email" value="{{ old('contact_email') }}"
                                   style="width: 100%; padding: 10px 14px; border: 1px solid #D1D5DB; border-radius: 8px; font-size: 0.875rem; outline: none;"
                                   placeholder="e.g. player@email.com">
                            @error('contact_email')
                                <p style="color: #DC2626; font-size: 0.8rem; margin-top: 4px;">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>

                {{-- Info Box --}}
                <div style="background: #FEF9EE; border: 1px solid #E8D5A8; border-radius: 8px; padding: 14px; margin-bottom: 24px;">
                    <p style="font-size: 0.8rem; color: #C8973A;">
                        ℹ️ Your help post will be reviewed by your school before it becomes visible on the website. 
                        Upload any supporting documents (medical certificates, financial proof, etc.) to speed up approval.
                    </p>
                </div>

                {{-- Submit --}}
                <div style="display: flex; gap: 12px; justify-content: flex-end;">
                    <a href="{{ route('player.help-posts.index') }}" style="padding: 10px 20px; background: #E5E7EB; color: #374151; border-radius: 8px; font-size: 0.875rem; text-decoration: none;">
                        Cancel
                    </a>
                    <button type="submit" style="padding: 10px 20px; background: #C8973A; color: white; border: none; border-radius: 8px; font-size: 0.875rem; cursor: pointer;">
                        Submit Help Post
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
